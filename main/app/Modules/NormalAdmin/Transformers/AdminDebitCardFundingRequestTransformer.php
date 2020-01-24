<?php

namespace App\Modules\NormalAdmin\Transformers;

use App\Modules\CardUser\Models\DebitCardFundingRequest;

class AdminDebitCardFundingRequestTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'funding_requests' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}
	public function transformForViewAllRequests(DebitCardFundingRequest $funding_request)
	{
		return [
			'id' => (int)$funding_request->id,
			'amount' => (double)$funding_request->amount,
			'card_number' => (int)$funding_request->debit_card->full_pan_number,
			'is_processed' => (boolean)$funding_request->is_funded,
			'card_user' => (object)$funding_request->card_user
		];
	}
}
