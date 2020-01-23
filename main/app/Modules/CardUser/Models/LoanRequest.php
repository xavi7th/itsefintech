<?php

namespace App\Modules\CardUser\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\CardUser\Transformers\LoanRequestTransformer;
use App\Modules\Admin\Transformers\AdminLoanRequestTransformer;
use App\Modules\CardUser\Http\Requests\CreateLoanRequestValidation;
use Watson\Rememberable\Rememberable;

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
			'total_interest_amount' => (float)round($total_interest_amount = ($this->monthly_interest / 100) * $this->amount * $this->total_duration, 2),
			'total_repayment_amount' => (float)round($total_repayment_amount = $total_interest_amount + $this->amount, 2),
			'scheduled_repayment_amount' => (float)number_format($total_repayment_amount / $this->total_duration, 2, '.', '')
		];
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('loan-requests/{admin?}', 'LoanRequest@showAllLoanRequests')->middleware('auth:admin');
			Route::put('loan-request/{loan_request}/approve', 'LoanRequest@approveLoanRequest')->middleware('auth:admin');
			Route::put('loan-request/{loan_request}/paid', 'LoanRequest@markLoanRequestAsPaid')->middleware('auth:admin');
		});
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('loan-request', 'LoanRequest@getLoanRequest')->middleware('auth:card_user');
			Route::get('loan-request/create', 'LoanRequest@getLoanRequestBreakdown')->middleware('auth:card_user');
			Route::post('loan-request/create', 'LoanRequest@makeLoanRequest')->middleware('auth:card_user');
		});
	}

	/**
	 * Card User Route Methods
	 */

	public function getLoanRequest(Request $request)
	{
		try {
			return (new LoanRequestTransformer)->transform($request->user()->loan_request);
		} catch (\Throwable $th) {
			return response()->json(['mesage' => 'User has no existing loan request'], 404);
		}
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
			'is_school_fees' => filter_var($request->is_school_fees, FILTER_VALIDATE_BOOLEAN)
		]);

		return response()->json((array)$loan_request->breakdownStatistics(), 201);
	}



	/**
	 * Admin Route Methods
	 */

	public function showAllLoanRequests($admin = null)
	{
		if (is_null($admin)) {
			$loan_requests = LoanRequest::withTrashed()->get();
		} else {
			$loan_requests = Admin::find($admin)->assigned_loan_requests()->withTrashed()->get();
		}
		return (new AdminLoanRequestTransformer)->collectionTransformer($loan_requests, 'transformForAdminViewLoanRequests');
	}

	public function approveLoanRequest(LoanRequest $loan_request)
	{
		$loan_request->approved_at = now();
		$loan_request->approved_by = auth()->id();
		$loan_request->save();

		return response()->json(['rsp' => []], 204);
	}

	public function markLoanRequestAsPaid(LoanRequest $loan_request)
	{
		DB::beginTransaction();

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

		DB::commit();
		return response()->json(['rsp' => []], 204);
	}
}
