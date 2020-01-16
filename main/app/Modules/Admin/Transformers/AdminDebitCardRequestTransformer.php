<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\CardUser\Models\DebitCardRequestStatus;

class AdminDebitCardRequestTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'debit_card_requests' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			}),
			'request_statuses' => DebitCardRequestStatus::all()
		];
	}
	public function transformForAdminViewDebitCardRequests(DebitCardRequest $debit_card_request)
	{
		return [
			'id' => (int)$debit_card_request->id,
			'city' => (string)$debit_card_request->city,
			'zip' => (string)$debit_card_request->zip,
			'address' => (string)$debit_card_request->address,
			'phone' => (string)$debit_card_request->phone,
			'payment_method' => (string)$debit_card_request->payment_method,
			'status' => (string)$debit_card_request->debit_card_request_status->name,
			'request_date' => (string)$debit_card_request->created_at->diffForHumans(),
			'requester' => $debit_card_request->card_user,
			'is_paid' => (boolean)$debit_card_request->is_paid,
			'is_payment_confirmed' => (boolean)$debit_card_request->is_payment_confirmed,
			'debit_card_request_status_id' => (int)$debit_card_request->debit_card_request_status_id,
			'debit_card_id' => (int)$debit_card_request->debit_card_id,
			'requested_card_type' => (string)$debit_card_request->debit_card_type->card_type_name,
		];
	}
}
