<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\Admin\Models\VoucherRequest;

class CardUserVoucherRequestTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'data' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transform(VoucherRequest $voucher_request)
	{
		return [
			'id' => $voucher_request->id,
			'amount' => (string)$voucher_request->amount,
			'is_appoved' => (boolean)$voucher_request->approved_at,
		];
	}

	public function transformWithLoanTransactions(VoucherRequest $voucher_request)
	{
		$transactions = $voucher_request->loan_transactions;
		$breakdown_statistics = $voucher_request->breakdownStatistics();
		return collect([
			'id' => $voucher_request->id,
			// 'amount' => $voucher_request->amount,
			'is_approved' => (boolean)$voucher_request->approved_at,
			'request_date' => $voucher_request->created_at->toDateString(),
			'total_paid' => (float)$total_paid = $transactions->where('transaction_type', 'repayment')->sum('amount'),
			'total_balance' => (float)$total_balance = ((object)$breakdown_statistics)->total_repayment_amount - $total_paid,
			'next_repayment_due_date' => $due_date = ($transactions->sortByDesc('id')->values()->first())->next_installment_due_date->toDateString(),
			'loan_defaulter' => (boolean)$total_balance == 0 ? false : now()->gte($due_date)
		])->merge($breakdown_statistics)->merge((new LoanTransactionTransformer)->collectionTransformer($transactions, 'transformForUserViewLoanTransactions'));
	}
}
