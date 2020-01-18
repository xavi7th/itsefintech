<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardUser\Models\DebitCardType;

class AdminDebitCardTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'debit_cards' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			}),
			'debit_card_types' => DebitCardType::get(['card_type_name', 'id'])
		];
	}
	public function transformForAdminViewDebitCards(DebitCard $card)
	{
		return [
			'id' => (int)$card->id,
			'card_number' => (string)$card->card_number,
			'year' => (string)$card->year,
			'month' => (string)$card->month,
			'csc' => (string)$card->csc,
			'card_user' => $card->card_user,
			'sales_rep' => $card->sales_rep,
			'is_suspended' => (boolean)$card->is_suspended,
			'is_user_activated' => (boolean)$card->is_user_activated,
			'is_admin_activated' => (boolean)$card->is_admin_activated,
			'amount' => (float)$card->debit_card_type->amount,
			'card_type' => (float)$card->debit_card_type->card_type_name
		];
	}
	public function transformForBasicDebitCardDetails(DebitCard $card)
	{
		return [
			'id' => (int)$card->id,
			'card_number' => (string)$card->card_number,
			'is_suspended' => (boolean)$card->is_suspended,
			'is_user_activated' => (boolean)$card->is_user_activated,
			'is_admin_activated' => (boolean)$card->is_admin_activated,
		];
	}
}
