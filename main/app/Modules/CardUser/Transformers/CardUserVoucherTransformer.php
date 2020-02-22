<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\Admin\Models\Voucher;


class CardUserVoucherTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'vouchers' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transformForCardUserListVouchers(Voucher $voucher)
	{
		return collect([
			'id' => (int)$voucher->id,
			'code' => (string)$voucher->code,
			'validity' => $voucher->created_at->addDays(config('app.voucher_validity_days'))->toDateString(),
			'transactions' => (new CardUserMerchantTransactionTransformer)->collectionTransformer($voucher->merchant_transactions, 'transform')['voucher_transactions'],
		])->merge($voucher->breakdownStatistics());
	}

	public function transformVoucherTransactions(Voucher $voucher)
	{
		return (new CardUserMerchantTransactionTransformer)->collectionTransformer($voucher->merchant_transactions, 'transform');
	}
}
