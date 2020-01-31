<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardUser\Http\Requests\CreateCardTransactionValidation;
use App\Modules\CardUser\Transformers\DebitCardTransactionsTransformer;
use App\Modules\Admin\Models\ActivityLog;

class DebitCardTransaction extends Model
{
	protected $fillable = [
		'card_user_id',
		'debit_card_id',
		'amount',
		'trans_description',
		'trans_category',
		'trans_type',
		'paystack_id',
		'paystack_ref',
		'paystack_message',
		'quickteller_req_ref',
		'quickteller_trans_ref',
		'quickteller_res_code',
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
			Route::get('debit-card-transactions', 'DebitCardTransaction@getCardUserCardTransactions')->middleware('auth:card_user');
			Route::get('debit-card-transactions/{debit_card}', 'DebitCardTransaction@getDebitCardTransactions')->middleware('auth:card_user');
			Route::post('debit-card-transaction/create', 'DebitCardTransaction@createCardTransaction')->middleware('auth:card_user');
		});
	}

	/**
	 * ! Card User route methods
	 */

	public function getCardUserCardTransactions()
	{
		return (new DebitCardTransactionsTransformer)->collectionTransformer(auth()->user()->debit_card_transactions, 'transform');
	}

	public function getDebitCardTransactions(DebitCard $debit_card)
	{
		return (new DebitCardTransactionsTransformer)->collectionTransformer($debit_card->debit_card_transactions->take(30), 'transform');
	}

	public function createCardTransaction(CreateCardTransactionValidation $request)
	{
		$card_trans = auth()->user()->debit_card_transactions()->create($request->all());

		ActivityLog::logUserActivity(auth()->user()->email . ' carried out a transaction on his card');

		return response()->json((new DebitCardTransactionsTransformer)->transform($card_trans), 201);
	}
}
