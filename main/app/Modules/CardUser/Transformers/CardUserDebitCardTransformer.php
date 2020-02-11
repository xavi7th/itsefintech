<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\CardUser\Models\DebitCard;

class CardUserDebitCardTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		try {
			return [
				'total' => $collection->count(),
				'current_page' => $collection->currentPage(),
				'path' => $collection->resolveCurrentPath(),
				'to' => $collection->lastItem(),
				'from' => $collection->firstItem(),
				'last_page' => $collection->lastPage(),
				'next_page_url' => $collection->nextPageUrl(),
				'per_page' => $collection->perPage(),
				'prev_page_url' => $collection->previousPageUrl(),
				'total' => $collection->total(),
				'first_page_url' => $collection->url($collection->firstItem()),
				'last_page_url' => $collection->url($collection->lastPage()),
				'data' => $collection->map(function ($v) use ($transformerMethod) {
					return $this->$transformerMethod($v);
				})
			];
		} catch (\Throwable $e) {
			return [
				'cards' => $collection->map(function ($v) use ($transformerMethod) {
					return $this->$transformerMethod($v);
				})
			];
		}
	}

	public function transformForCardDetails(DebitCard $debit_card)
	{
		return [
			'id' => $debit_card->id,
			'debit_card_type' => (string)$debit_card->debit_card_type->card_type_name,
			'card_number' => $debit_card->card_number,
			'year' => $debit_card->year,
			'cardholder' => auth()->user()->first_name,
		];
	}

	public function transformForCardList(DebitCard $debit_card)
	{
		return [
			'id' => (int)$debit_card->id,
			'debit_card_type' => (string)$debit_card->debit_card_type->card_type_name,
			'card_number' => (int)$debit_card->card_number,
			'full_card_number' => (int)$debit_card->full_pan_number,
			'cvv' => (int)$debit_card->csc,
			'year' => (int)$debit_card->year,
			'month' => (int)substr($debit_card->month, -2),
			'cardholder' => (string)auth()->user()->first_name,
			'is_user_activated' => (boolean)$debit_card->is_user_activated
		];
	}
}
