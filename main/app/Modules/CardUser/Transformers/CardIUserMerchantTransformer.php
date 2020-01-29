<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\Admin\Models\Merchant;


class CardIUserMerchantTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'merchants' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transform(Merchant $merchant)
	{
		return [
			'id' => (int)$merchant->id,
			'name' => (string)$merchant->name,
			'address' => (string)$merchant->address,
			'category' => (string)optional($merchant->merchant_category)->name,
			'img' => (string)asset($merchant->merchant_img)

		];
	}
}
