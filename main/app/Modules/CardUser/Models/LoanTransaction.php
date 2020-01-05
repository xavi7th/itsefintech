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
use App\Modules\Admin\Transformers\AdminLoanTransactionTransformer;
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

	public function showAllLoanTransactions()
	{

		$loan_requests = LoanTransaction::all();

		return (new AdminLoanTransactionTransformer)->collectionTransformer($loan_requests, 'transformForAdminViewLoanTransactions');
	}
}
