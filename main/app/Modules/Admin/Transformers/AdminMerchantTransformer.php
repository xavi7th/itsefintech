<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\Admin\Models\Merchant;


class AdminMerchantTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'merchants' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transformForAdminViewMerchants(Merchant $merchant)
	{
		return [
			'id' => (int)$merchant->id,
			'name' => (string)$merchant->name,
			'unique_code' => (string)$merchant->unique_code,
			'email' => (string)$merchant->email,
			'phone' => (string)$merchant->phone,
			'is_active' => (boolean)$merchant->is_active,

		];
	}
}
