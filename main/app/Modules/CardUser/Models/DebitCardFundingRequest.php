<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Http\Requests\RequestDebitCardFundingValidation;
use App\Modules\CardUser\Transformers\DebitCardFundingRequestTransformer;
use App\Modules\NormalAdmin\Transformers\AdminDebitCardFundingRequestTransformer;

class DebitCardFundingRequest extends Model
{
	use SoftDeletes, Rememberable;

	protected $rememberFor = 30;

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

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('debit-card-funding-requests', 'DebitCardFundingRequest@getAllFundingRequests')->middleware('auth:admin');
			Route::put('debit-card-funding-request/{funding_request}/process', 'DebitCardFundingRequest@markProcessed')->middleware('auth:admin');
		});
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::post('debit-card-funding-request/create', 'DebitCardFundingRequest@requestDebitCardFunding')->middleware('auth:card_user');
			Route::get('debit-card-funding-request/status', 'DebitCardFundingRequest@checkDebitCardFundingStatus')->middleware('auth:card_user');
		});
	}

	/**
	 * ! Card User routes
	 */

	public function requestDebitCardFunding(RequestDebitCardFundingValidation $request)
	{
		$fund_request = DebitCard::find($request->debit_card_id)->debit_card_funding_request()->create([
			'card_user_id' => auth()->user()->id,
			'amount' => $request->amount
		]);

		return response()->json((new DebitCardFundingRequestTransformer)->transform($fund_request), 201);
	}

	public function checkDebitCardFundingStatus()
	{
		$funding_request = auth()->user()->debit_card_funding_request;
		if ($funding_request) {
			return (new DebitCardFundingRequestTransformer)->transform($funding_request);
		} else {
			return response()->json(['message' => 'User has no funding request'], 404);
		}
	}

	/**
	 * ! Admin Routes
	 */
	public function getAllFundingRequests()
	{
		return (new AdminDebitCardFundingRequestTransformer)->collectionTransformer(DebitCardFundingRequest::withTrashed()->dontRemember()->get(), 'transformForViewAllRequests');
	}

	public function markProcessed(DebitCardFundingRequest $funding_request)
	{
		DB::beginTransaction();
		/**
		 * Create a credit transaction for this debit card
		 */
		$debit_card = new DebitCardTransaction();
		$debit_card->card_user_id = $funding_request->card_user_id;
		$debit_card->debit_card_id = $funding_request->debit_card_id;
		$debit_card->amount = $funding_request->amount;
		$debit_card->trans_description = 'Card funding';
		$debit_card->trans_category = 'Card funding';
		$debit_card->trans_type = 'credit';
		$debit_card->save();


		/**
		 * Mark the funding request as processed
		 */
		$funding_request->is_funded = true;
		$funding_request->save();

		DB::commit();
		return response()->json([], 204);
	}
}
