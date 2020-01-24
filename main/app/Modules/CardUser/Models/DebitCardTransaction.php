<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardUser\Http\Requests\CreateCardTransactionValidation;
use App\Modules\CardUser\Transformers\DebitCardTransactionsTransformer;

class DebitCardTransaction extends Model
{
	protected $fillable = [
		'card_user_id',
		'debit_card_id',
		'amount',
		'trans_description',
		'trans_category',
		'trans_type',
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
			Route::post('debit-card-transaction/create', 'DebitCardTransaction@createCardTransaction')->middleware('auth:card_user');
		});
	}

	public function getCardUserCardTransactions()
	{
		return (new DebitCardTransactionsTransformer)->collectionTransformer(auth()->user()->debit_card_transactions, 'transform');
	}

	public function createCardTransaction(CreateCardTransactionValidation $request)
	{
		$card_trans = auth()->user()->debit_card_transactions()->create($request->all());
		return response()->json((new DebitCardTransactionsTransformer)->transform($card_trans), 201);
	}
}
