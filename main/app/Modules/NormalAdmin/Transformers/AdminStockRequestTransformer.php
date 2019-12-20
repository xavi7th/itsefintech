<?php

namespace App\Modules\NormalAdmin\Transformers;

use App\Modules\NormalAdmin\Models\StockRequest;


class AdminStockRequestTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'stock_requests' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}
	public function transformForAdminViewStockRequests(StockRequest $stock_request)
	{
		return [
			'id' => (int)$stock_request->id,
			'sales_rep' => (object)$stock_request->sales_rep,
			'number_of_cards' => (integer)$stock_request->number_of_cards,
			'is_processed' => (bool)$stock_request->is_processed,
		];
	}
}
