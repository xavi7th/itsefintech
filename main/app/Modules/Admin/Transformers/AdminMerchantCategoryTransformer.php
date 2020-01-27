<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\Admin\Models\MerchantCategory;


class AdminMerchantCategoryTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'merchant_categories' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transformForAdminViewMerchantCategories(MerchantCategory $merchant_categories)
	{
		return [
			'id' => (int)$merchant_categories->id,
			'name' => (string)$merchant_categories->name,
		];
	}
}
