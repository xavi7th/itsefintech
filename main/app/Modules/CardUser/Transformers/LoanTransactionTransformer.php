<?php

namespace App\Modules\CardUser\Transformers;

use Illuminate\Http\Request;
use App\Modules\CardUser\Models\Transaction;
use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\CardUser\Models\WithdrawalRequest;

class LoanTransactionTransformer
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
				'loan_transactions' => $collection->map(function ($v) use ($transformerMethod) {
					return $this->$transformerMethod($v);
				})
			];
		}
	}

	public function transformForUserViewLoanTransactions(LoanTransaction $loan_transaction)
	{
		return [
			'id' => (int)$loan_transaction->id,
			'amount' => (float)$loan_transaction->amount,
			'transaction_type' => (string)$loan_transaction->transaction_type,
		];
	}
	public function transformForSummary(Request $request)
	{
		return [
			'total_loan_amount' => (float)$request->user()->total_loan_amount(),
			'total_repayments_amount' => (float)$request->user()->total_repayment_amount(),
			'total_loan_balance' => (float)$request->user()->total_loan_amount() - $request->user()->total_repayment_amount(),
			// 'next_repayment_due_date' => $due_date = ($transaction = $request->user()->loan_transactions()->latest()->first())->next_installment_due_date,
			// 'next_repayment_minimum_amount' => (float)$transaction->loan_request->repayment_amount,
			// 'loan_defaulter' => (boolean)now()->gte($due_date)
		];
	}
}
