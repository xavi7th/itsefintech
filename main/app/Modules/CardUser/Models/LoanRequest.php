<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Transformers\LoanRequestTransformer;
use App\Modules\Admin\Transformers\AdminLoanRequestTransformer;
use App\Modules\CardUser\Http\Requests\CreateLoanRequestValidation;

class LoanRequest extends Model
{
	use SoftDeletes;

	protected $fillable = ['amount', 'total_duration', 'repayment_duration', 'repayment_amount',];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
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
			return response()->json(['mesage' => 'No request found'], 404);
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
}
