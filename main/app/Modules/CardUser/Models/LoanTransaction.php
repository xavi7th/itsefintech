<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\LoanRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Transformers\LoanRequestTransformer;
use App\Modules\CardUser\Transformers\LoanTransactionTransformer;
use App\Modules\CardUser\Http\Requests\MakeLoanRepaymentValidation;

class LoanTransaction extends Model
{
	use SoftDeletes;
	use Rememberable;

	protected $fillable = ['card_user_id', 'amount', 'transaction_type', 'next_installment_due_date',];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'next_installment_due_date',
	];

	public function loan_request()
	{
		return $this->belongsTo(LoanRequest::class);
	}

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}


	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('loan-transactions', 'LoanTransaction@showAllLoanTransactions')->middleware('auth:admin');
		});
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('loan-transactions', 'LoanTransaction@getLoanTransaction')->middleware('auth:card_user');
			Route::get('loan-transactions/summary', 'LoanTransaction@getLoanTransactionSummary')->middleware('auth:card_user');
			Route::post('loan-transaction/{loan_request}/make-payment', 'LoanTransaction@makeLoanRepayment')->middleware('auth:card_user');
		});
	}

	/**
	 * Card User Route Methods
	 */

	public function getLoanTransaction(Request $request)
	{
		try {
			return collect((new LoanTransactionTransformer)->transformForSummary($request))->merge((new LoanTransactionTransformer)->collectionTransformer($request->user()->loan_transactions, 'transformForUserViewLoanTransactions'));
		} catch (\Throwable $th) {
			return response()->json(['mesage' => 'User has no existing loan transaction'], 404);
		}
	}

	public function getLoanTransactionSummary(Request $request)
	{
		return (new LoanRequestTransformer)->collectionTransformer($request->user()->running_loan_requests, 'transformWithLoanTransactions');
	}

	public function makeLoanRepayment(MakeLoanRepaymentValidation $request, LoanRequest $loan_request)
	{
		return $loan_request->loan_transactions()->create([
			'card_user_id' => auth()->id(),
			'amount' => $request->amount,
			'transaction_type' => 'repayment',
			'next_installment_due_date' => now()->addDays($loan_request->repayment_duration),
		]);
		return (new LoanTransactionTransformer)->transform($request->user()->loan_request()->create($request->all()));
	}

	/**
	 * Admin Route Methods
	 */

	public function showAllLoanTransactions($admin = null)
	{
		if (is_null($admin)) {
			$loan_requests = LoanTransaction::withTrashed()->get();
		} else {
			$loan_requests = Admin::find($admin)->assigned_loan_requests()->withTrashed()->get();
		}
		return (new AdminLoanTransactionTransformer)->collectionTransformer($loan_requests, 'transformForAdminViewLoanTransactions');
	}

	public function approveLoanTransaction(LoanTransaction $loan_request)
	{
		$loan_request->approved_at = now();
		$loan_request->approved_by = auth()->id();
		$loan_request->save();

		return response()->json(['rsp' => []], 204);
	}

	public function markLoanTransactionAsPaid(LoanTransaction $loan_request)
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
