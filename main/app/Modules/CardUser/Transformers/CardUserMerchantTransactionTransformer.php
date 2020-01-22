<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\Admin\Models\MerchantTransaction;


class CardUserMerchantTransactionTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'voucher_transactions' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transform(MerchantTransaction $merchant_transaction)
	{
		return [
			'id' => (int)$merchant_transaction->id,
			'voucher_code' => (string)$merchant_transaction->voucher->code,
			'merchant' => (string)$merchant_transaction->merchant->name,
			'amount' => (string)$merchant_transaction->amount,
			'trans_date' => (string)$merchant_transaction->created_at->diffForHumans(),

		];
	}

	public function transformForMerchantView(MerchantTransaction $merchant_transaction)
	{
		return [
			'id' => (int)$merchant_transaction->id,
			'voucher_code' => (string)$merchant_transaction->voucher->code,
			'user' => (string)$merchant_transaction->card_user->full_name,
			'amount' => (string)$merchant_transaction->amount,
			'trans_date' => (string)$merchant_transaction->created_at->diffForHumans(),
			'transaction_approved' => (bool)($merchant_transaction->trans_type === 'debit')

		];
	}
}
