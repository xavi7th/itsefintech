<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\CardUser\Models\DebitCardType;

class AdminDebitCardTypeTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'debit_card_types' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transformForAdminViewDebitCardTypes(DebitCardType $debit_card_type)
	{
		return [
			'id' => (int)$debit_card_type->id,
			'card_type_name' => (string)$debit_card_type->card_type_name,
			'amount' => (float)$debit_card_type->amount
		];
	}
}
