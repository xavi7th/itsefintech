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
			'amount' => (string)$voucher->amount,
			'is_expired' => $voucher->is_expired,
			'transactions' => (new CardUserMerchantTransactionTransformer)->collectionTransformer($voucher->merchant_transactions, 'transform')['voucher_transactions'],
			'amount_left' => $voucher->amount_left,
			'amount_spent' => $voucher->amount_spent,
			'repayment_balance' => $voucher->repayment_balance,
		])->merge($voucher->breakdownStatistics());
	}
}
