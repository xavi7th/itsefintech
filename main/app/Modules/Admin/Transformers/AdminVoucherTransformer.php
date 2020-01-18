<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\Admin\Models\Voucher;


class AdminVoucherTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'vouchers' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transformForAdminViewVouchers(Voucher $voucher)
	{
		return [
			'id' => (int)$voucher->id,
			'code' => (string)$voucher->code,
			'amount' => (string)$voucher->amount,
			'is_expired' => $voucher->is_expired,
			'card_user' => $voucher->card_user

		];
	}
}
