<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\Admin\Models\Admin;
use App\Modules\Admin\Models\VoucherRequest;
use App\Modules\Admin\Transformers\AdminUserTransformer;

class AdminVoucherRequestTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'voucher_requests' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}
	public function transformForAdminViewVoucherRequests(VoucherRequest $voucher_request)
	{
		$card_user = $voucher_request->card_user;
		return [
			'id' => (int)$voucher_request->id,
			'voucher_id' => (int)$voucher_request->voucher_id,
			'amount' => $voucher_request->amount,
			'monthly_interest' => (string)((float)$card_user->merchant_percentage) . '%',
			'repayment_balance' => (float)optional($voucher_request->voucher)->repayment_balance,
			'is_approved' => (boolean)$voucher_request->approved_at,
			'approved_by' => $voucher_request->approved_by ? (new AdminUserTransformer)->transformForAdminViewAdminsBasicDetails(Admin::find($voucher_request->approved_by))['full_name'] : null,
			'requester' => (new AdminUserTransformer)->transformForAdminViewCardUsers($card_user)
		];
	}
}
