<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\CardUser\Models\Transaction;
use App\Modules\CardUser\Models\WithdrawalRequest;

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
			'amount' => $loan_request->amount,
			'total_duration' => (int)$loan_request->total_duration,
			'repayment_duration' => (int)$loan_request->repayment_duration,
			'repayment_amount' => (float)$loan_request->repayment_amount,
			'is_approved' => (boolean)$loan_request->approved_at,
		];
	}

	public function transformWithLoanTransactions(LoanRequest $loan_request)
	{
		$transactions = $loan_request->loan_transactions;
		return [
			'id' => $loan_request->id,
			'amount' => $loan_request->amount,
			'total_duration' => (int)$loan_request->total_duration,
			'repayment_duration' => (int)$loan_request->repayment_duration,
			'repayment_amount' => (float)$loan_request->repayment_amount,
			'is_approved' => (boolean)$loan_request->approved_at,
			'request_date' => $loan_request->created_at,
			// 'transactions' => (object)$transactions,
			'total_paid' => (float)$transactions->where('transaction_type', 'repayment')->sum('amount'),
			'total_balance' => (float)$loan_request->amount - $transactions->where('transaction_type', 'repayment')->sum('amount'),
			'next_repayment_due_date' => $due_date = ($transactions->sortByDesc('id')->values()->first())->next_installment_due_date,
			// 'next_repayment_due_date' => $due_date = ($transactions->latest()->first())->next_installment_due_date,
			'next_repayment_minimum_amount' => (float)$loan_request->repayment_amount,
			'loan_defaulter' => (boolean)now()->gte($due_date)
		];
	}
}
