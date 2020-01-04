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

	protected $fillable = ['amount', 'total_duration', 'repayment_duration', 'repayment_amount',];

	protected $appends = ['due_date'];

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
		return $this->amount - $this->loan_transactions()->where('transaction_type', 'repayment')->sum('amount');
	}

	public function getDueDateAttribute()
	{
		return Carbon::parse($this->attributes['created_at'])->addDays($this->attributes['total_duration'])->toDateString();
	}

	static function minimumRepaymentAmount(float $amount, int $total_days, int $repayment_days)
	{

		$percentage = (100 * $repayment_days) / $total_days;

		return ($percentage / 100) * $amount;
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

	public function makeLoanRequest(CreateLoanRequestValidation $request)
	{
		return (new LoanRequestTransformer)->transform($request->user()->loan_request()->create($request->all()));
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
				'amount' => $loan_request->amount,
				'transaction_type' => 'loan',
				'next_installment_due_date' => now()->addDays($loan_request->repayment_duration),
			]
		);

		DB::commit();
		return response()->json(['rsp' => []], 204);
	}
}
