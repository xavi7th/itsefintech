<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\CardUser\Models\Transaction;
use App\Modules\CardUser\Models\WithdrawalRequest;
use App\Modules\CardUser\Transformers\LoanTransactionTransformer;

class LoanRequestTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		try {
			return [
				'total' => $collection->count(),
				'current_page' => $collection->currentPage(),
				'path' => $collection->resolveCurrentPath(),
				'to' => $collection->lastItem(),
				'from' => $collection->firstItem(),
				'last_page' => $collection->lastPage(),
				'next_page_url' => $collection->nextPageUrl(),
				'per_page' => $collection->perPage(),
				'prev_page_url' => $collection->previousPageUrl(),
				'total' => $collection->total(),
				'first_page_url' => $collection->url($collection->firstItem()),
				'last_page_url' => $collection->url($collection->lastPage()),
				'data' => $collection->map(function ($v) use ($transformerMethod) {
					return $this->$transformerMethod($v);
				})
			];
		} catch (\Throwable $e) {
			return [
				'data' => $collection->map(function ($v) use ($transformerMethod) {
					return $this->$transformerMethod($v);
				})
			];
		}
	}

	public function transform(LoanRequest $loan_request)
	{
		return [
			'id' => $loan_request->id,
			'total_duration' => (string)$loan_request->total_duration . ' months',
			'breakdown' => $loan_request->breakdownStatistics(),
			'is_approved' => (boolean)$loan_request->approved_at,
			'is_paid' => (boolean)$loan_request->paid_at,
		];
	}

	public function transformWithLoanTransactions(LoanRequest $loan_request)
	{
		$transactions = $loan_request->loan_transactions;
		$mod_transactions = $loan_request->loan_transactions->slice(1)->values();
		$breakdown_statistics = $loan_request->breakdownStatistics();
		return collect([
			'id' => $loan_request->id,
			// 'amount' => $loan_request->amount,
			'is_approved' => (boolean)$loan_request->approved_at,
			'request_date' => $loan_request->created_at->toDateString(),
			'total_paid' => (float)$total_paid = $transactions->where('transaction_type', 'repayment')->sum('amount'),
			'total_balance' => (float)$total_balance = ((object)$breakdown_statistics)->total_repayment_amount - $total_paid,
			'next_repayment_due_date' => $due_date =  $transactions->isEmpty() ? null : ($transactions->sortByDesc('id')->values()->first())->next_installment_due_date->toDateString(),
			'loan_defaulter' => (boolean)$total_balance == 0 && !is_null($due_date)   ? false : now()->gte($due_date),
			'payment_complete' => (boolean)$total_balance
		])->merge($breakdown_statistics)->merge((new LoanTransactionTransformer)->collectionTransformer($mod_transactions, 'transformForUserViewLoanTransactions'));
	}
}
