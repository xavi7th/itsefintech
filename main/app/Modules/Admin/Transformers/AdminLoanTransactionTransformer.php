<?php

namespace App\Modules\Admin\Transformers;

use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\Admin\Models\Admin;

class AdminLoanTransactionTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'loan_transactions' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}
	public function transformForAdminViewLoanTransactions(LoanTransaction $loan_transaction)
	{
		return [
			'id' => (int)$loan_transaction->id,
			'amount' => $loan_transaction->amount,
			'transaction_type' => (string)$loan_transaction->transaction_type,
			'next_installment_due_date' => $loan_transaction->next_installment_due_date,
			'created_at' => $loan_transaction->created_at,
			'requester' => (new AdminUserTransformer)->transformForAdminViewCardUsers($loan_transaction->card_user)
		];
	}
}
