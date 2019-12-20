<?php

namespace App\Modules\SalesRep\Transformers;

use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\CardUser\Models\DebitCardRequestStatus;

class SalesRepDebitCardRequestTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'debit_card_requests' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}
	public function transformForSalesRepViewSales(DebitCardRequest $debit_card_request)
	{
		return [
			'id' => (int)$debit_card_request->id,
			'phone' => (string)$debit_card_request->phone,
			'payment_method' => (string)$debit_card_request->payment_method,
			'request_date' => (string)$debit_card_request->created_at->diffForHumans(),
			'customer' => (string)$debit_card_request->card_user->first_name . ' ' . $debit_card_request->card_user->last_name,
			'is_paid' => (boolean)$debit_card_request->is_paid,
			'is_payment_confirmed' => (boolean)$debit_card_request->is_payment_confirmed,
			'debit_card' => (int)$debit_card_request->debit_card->card_number,
		];
	}
}
