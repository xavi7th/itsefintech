<?php

namespace App\Modules\CardUser\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\Admin\Notifications\LoanApproved;
use App\Modules\Admin\Notifications\LoanProcessed;
use App\Modules\CardUser\Notifications\LoanRequested;
use App\Modules\CardUser\Transformers\LoanRequestTransformer;
use App\Modules\Admin\Transformers\AdminLoanRequestTransformer;
use App\Modules\CardUser\Http\Requests\CreateLoanRequestValidation;

class LoanRequest extends Model
{
	use SoftDeletes, Rememberable;

	protected $fillable = ['amount', 'total_duration', 'monthly_interest', 'is_school_fees'];

	protected $appends = ['due_date'];

	protected $casts = ['is_school_fees' => 'boolean'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'due_date', 'approved_at', 'paid_at'
	];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function loan_transactions()
	{
		return $this->hasMany(LoanTransaction::class);
	}

	public function loan_balance()
	{
		return $this->breakdownStatistics()->total_repayment_amount - $this->loan_transactions()->where('transaction_type', 'repayment')->sum('amount');
	}

	public function getDueDateAttribute()
	{
		return Carbon::parse($this->attributes['created_at'])->addMonths($this->attributes['total_duration'])->toDateString();
	}

	public function breakdownStatistics(): object
	{
		return (object)[
			'amount' => (float)$this->amount,
			'is_school_fees' => (boolean)$this->is_school_fees,
			'interest_rate' => (float)$this->monthly_interest,
			'total_duration' => (string)$this->total_duration . ' months',
			'minimum_repayment_amount' => (float)round($this->amount * ($this->monthly_interest / 100), 2),
			'minimum_repayment_amount_in_kobo' => (float)(round($this->amount * ($this->monthly_interest / 100), 2) * 100),
			'total_interest_amount' => (float)round($total_interest_amount = ($this->monthly_interest / 100) * $this->amount * (now()->diffInMonths(($this->paid_at)) + 1), 2),
			'total_repayment_amount' => (float)round($total_repayment_amount = $total_interest_amount + $this->amount, 2),
			'scheduled_repayment_amount' => (float)number_format($total_repayment_amount / $this->total_duration, 2, '.', ''),
			'scheduled_repayment_amount_in_kobo' => (float)(number_format($total_repayment_amount / $this->total_duration, 2, '.', '') * 100)
		];
	}

	static function normalAdminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models', 'prefix' => 'api'], function () {
			Route::put('loan-request/{loan_request}/approve', 'LoanRequest@approveLoanRequest')->middleware('auth:normal_admin');
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('loan-requests/', 'LoanRequest@showAllLoanRequests')->middleware('auth:admin,normal_admin,accountant');
			Route::put('loan-request/{loan_request}/paid', 'LoanRequest@markLoanRequestAsPaid')->middleware('auth:accountant');
		});
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models', 'middleware' => ['verified_card_users']], function () {
			Route::get('loan-request', 'LoanRequest@getLoanRequest')->middleware('auth:card_user');
			Route::get('{loan_request}/loan-transactions', 'LoanRequest@getLoanRequestTransactions')->middleware('auth:card_user');
			Route::get('loan-request/create', 'LoanRequest@getLoanRequestBreakdown')->middleware('auth:card_user');
			Route::post('loan-request/create', 'LoanRequest@makeLoanRequest')->middleware('auth:card_user');
		});
	}

	/**
	 * ! Card User Route Methods
	 */

	public function getLoanRequest(Request $request)
	{
		try {
			return (new LoanRequestTransformer)->collectionTransformer($request->user()->loan_request, 'transform');
		} catch (\Throwable $th) {
			return response()->json(['mesage' => 'User has no existing loan request'], 404);
		}
	}

	public function getLoanRequestTransactions(LoanRequest $loan_request)
	{
		return (new LoanRequestTransformer)->transformLoanTransactions($loan_request);
	}

	public function getLoanRequestBreakdown(CreateLoanRequestValidation $request)
	{
		$loan_request = resolve(LoanRequest::class);
		$loan_request->amount = (float)$request->input('amount');
		$loan_request->monthly_interest = auth()->user()->credit_percentage;
		$loan_request->total_duration = (int)$request->input('total_duration');
		return (array)$loan_request->breakdownStatistics();
	}

	public function makeLoanRequest(CreateLoanRequestValidation $request)
	{

		$loan_request = $request->user()->loan_request()->create([
			'amount' => $request->input('amount'),
			'total_duration' => $request->input('total_duration'),
			'monthly_interest' => auth()->user()->credit_percentage,
			'is_school_fees' => $is_school_fees_loan = filter_var($request->is_school_fees, FILTER_VALIDATE_BOOLEAN)
		]);

		if ($is_school_fees_loan) {
			ActivityLog::logUserActivity(auth()->user()->email . ' made a school fees loan request of ' . $request->input('amount'));
			ActivityLog::notifyAccountOfficers(auth()->user()->email . ' made a school fees loan request of ' . $request->input('amount'));
			ActivityLog::notifyAccountants(auth()->user()->email . ' made a school fees loan request of ' . $request->input('amount'));
			ActivityLog::notifyAdmins(auth()->user()->email . ' made a school fees loan request of ' . $request->input('amount'));
			ActivityLog::notifyNormalAdmins(auth()->user()->email . ' made a school fees loan request of ' . $request->input('amount'));
			auth()->user()->notify(new LoanRequested($request->input('amount'), true));
		} else {
			ActivityLog::logUserActivity(auth()->user()->email . ' made a loan request of ' . $request->input('amount'));
			ActivityLog::notifyAccountOfficers(auth()->user()->email . ' made a loan request of ' . $request->input('amount'));
			ActivityLog::notifyAccountants(auth()->user()->email . ' made a loan request of ' . $request->input('amount'));
			ActivityLog::notifyAdmins(auth()->user()->email . ' made a loan request of ' . $request->input('amount'));
			ActivityLog::notifyNormalAdmins(auth()->user()->email . ' made a loan request of ' . $request->input('amount'));
			auth()->user()->notify(new LoanRequested($request->input('amount')));
		}
		return response()->json((array)$loan_request->breakdownStatistics(), 201);
	}



	/**
	 * !Admin Route Methods
	 */

	public function showAllLoanRequests($admin = null)
	{
		if (auth('admin')->check()) {
			$loan_requests = LoanRequest::withTrashed()->get();
		} else if (auth('normal_admin')->check()) {
			$loan_requests = LoanRequest::where('approved_at', null)->get();
		} else if (auth('accountant')->check()) {
			$loan_requests = LoanRequest::where('approved_at', '<>', null)->get();
		}
		return (new AdminLoanRequestTransformer)->collectionTransformer($loan_requests, 'transformForAdminViewLoanRequests');
	}

	public function approveLoanRequest(LoanRequest $loan_request)
	{
		$card_user = $loan_request->card_user;

		$loan_request->approved_at = now();
		$loan_request->approved_by = auth()->id();
		$loan_request->save();

		ActivityLog::notifyAccountOfficers(auth()->user()->email . ' approved a loan request of ' . $loan_request->amount . ' for ' . $card_user->email);
		ActivityLog::notifyAccountants(auth()->user()->email . ' approved a loan request of ' . $loan_request->amount . ' for ' . $card_user->email);
		ActivityLog::notifyAdmins(auth()->user()->email . ' approved a loan request of ' . $loan_request->amount . ' for ' . $card_user->email);
		ActivityLog::notifyNormalAdmins(auth()->user()->email . ' approved a loan request of ' . $loan_request->amount . ' for ' . $card_user->email);

		$card_user->notify(new LoanApproved($loan_request->amount, $loan_request->is_school_fees));

		return response()->json(['rsp' => []], 204);
	}

	public function markLoanRequestAsPaid(LoanRequest $loan_request)
	{
		DB::beginTransaction();

		$card_user = $loan_request->card_user;

		$loan_request->paid_at = now();
		$loan_request->marked_paid_by = auth()->id();
		$loan_request->save();

		$loan_request->loan_transactions()->updateOrCreate(
			[
				'loan_request_id' => $loan_request->id
			],
			[
				'card_user_id' => $loan_request->card_user_id,
				'amount' => ((object)$loan_request->breakdownStatistics())->total_repayment_amount,
				'transaction_type' => $loan_request->is_school_fees ? 'school fees loan' : 'loan',
				'next_installment_due_date' => now()->addMonth(),
			]
		);

		ActivityLog::notifyAccountOfficers(auth()->user()->email . ' marked ' .  $card_user->email . '\'s loan request of ' . $loan_request->amount . ' as paid.');
		ActivityLog::notifyAccountants(auth()->user()->email . ' marked ' .  $card_user->email . '\'s loan request of ' . $loan_request->amount . ' as paid.');
		ActivityLog::notifyAdmins(auth()->user()->email . ' marked ' .  $card_user->email . '\'s loan request of ' . $loan_request->amount . ' as paid.');
		ActivityLog::notifyNormalAdmins(auth()->user()->email . ' marked ' .  $card_user->email . '\'s loan request of ' . $loan_request->amount . ' as paid.');

		DB::commit();

		$card_user->notify(new LoanProcessed($loan_request->amount, $loan_request->is_school_fees));

		return response()->json(['rsp' => []], 204);
	}
}
