<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\Admin\Models\Admin;

class AdminLoanRequestTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'loan_requests' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}
	public function transformForAdminViewLoanRequests(LoanRequest $loan_request)
	{
		return [
			'id' => (int)$loan_request->id,
			'amount' => $loan_request->amount,
			'total_duration' => (int)$loan_request->total_duration,
			'due_date' => $loan_request->due_date,
			'repayment_duration' => (int)$loan_request->repayment_duration,
			'repayment_amount' => (float)$loan_request->repayment_amount,
			'loan_balance' => (float)$loan_request->loan_balance(),
			'is_approved' => (boolean)$loan_request->approved_at,
			'approved_by' => $loan_request->approved_by ? (new AdminUserTransformer)->transformForAdminViewAdminsBasicDetails(Admin::find($loan_request->approved_by))['full_name'] : null,
			'is_paid' => (boolean)$loan_request->paid_at,
			'paid_by' => $loan_request->marked_paid_by ? (new AdminUserTransformer)->transformForAdminViewAdminsBasicDetails(Admin::find($loan_request->marked_paid_by))['full_name'] : null,
			'created_at' => $loan_request->created_at,
			'updated_at' => $loan_request->updated_at,
			'requester' => (new AdminUserTransformer)->transformForAdminViewCardUsers($loan_request->card_user)
		];
	}
}
