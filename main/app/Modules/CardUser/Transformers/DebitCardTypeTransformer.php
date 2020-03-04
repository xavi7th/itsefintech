<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\CardUser\Models\DebitCardType;

class DebitCardTypeTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'card_types' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transform(DebitCardType $card_type)
	{
		return [
			'id' => (int)$card_type->id,
			'card_type_name' => (string)$card_type->card_type_name,
			'amount' => (double)$card_type->amount,
			'max_amount' => (double)$card_type->max_amount,
		];
	}
}
