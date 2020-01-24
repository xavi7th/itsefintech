<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardUser\Http\Requests\RequestDebitCardFundingValidation;

class DebitCardFundingRequest extends Model
{
	protected $fillable = [
		'card_user_id',
		'amount',
	];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function debit_card()
	{
		return $this->belongsTo(DebitCard::class);
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::post('debit-card-funding-request/create', 'DebitCardFundingRequest@requestDebitCardFunding')->middleware('auth:card_user');
		});
	}

	public function requestDebitCardFunding(RequestDebitCardFundingValidation $request)
	{
		$fund_request = DebitCard::find($request->debit_card_id)->debit_card_funding_request()->create([
			'card_user_id' => auth()->user()->id,
			'amount' => $request->amount
		]);

		return response()->json(['rsp' => $fund_request], 201);
	}
}
