<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\SoftDeletes;
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
			Route::get('debit-cards/{rep?}', function ($rep = null) {
				if (is_null($rep)) {
					$debit_cards = DebitCard::withTrashed()->get();
				} else {
					$debit_cards = SalesRep::find($rep)->assigned_debit_cards()->withTrashed()->get();
				}
				return (new AdminDebitCardTransformer)->collectionTransformer($debit_cards, 'transformForAdminViewDebitCards');
			})->middleware('auth:admin');


			Route::delete('debit-card/{debit_card}/delete', function (DebitCard $debit_card) {
				return;
				$debit_card->delete();
				return response()->json(['rsp' => true], 204);
			})->middleware('auth:admin');
		});
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::post('loan-request/create', 'LoanRequest@makeLoanRequest')->middleware('auth:card_user');
		});
	}

	public function makeLoanRequest(CreateLoanRequestValidation $request)
	{
		return $request->user()->loan_request()->create($request->all());
	}
}
