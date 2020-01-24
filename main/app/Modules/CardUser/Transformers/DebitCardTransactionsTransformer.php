<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\CardUser\Models\DebitCardTransaction;

class DebitCardTransactionsTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'card_transactions' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transform(DebitCardTransaction $card_transaction)
	{
		return [
			'id' => (int)$card_transaction->id,
			'card' => (int)$card_transaction->debit_card->card_number,
			'amount' => (double)$card_transaction->amount,
			'trans_description' => (string)$card_transaction->trans_description,
			'trans_category' => (string)$card_transaction->trans_category,
			'trans_type' => (string)$card_transaction->trans_type,
			'trans_date' => (string)$card_transaction->created_at->toDateString(),
		];
	}

	// public function transformWithLoanTransactions(DebitCardTransaction $card_transaction)
	// {
	// 	$transactions = $card_transaction->loan_transactions;
	// 	$breakdown_statistics = $card_transaction->breakdownStatistics();
	// 	return collect([
	// 		'id' => $card_transaction->id,
	// 		// 'amount' => $card_transaction->amount,
	// 		'is_approved' => (boolean)$card_transaction->approved_at,
	// 		'request_date' => $card_transaction->created_at->toDateString(),
	// 		'total_paid' => (float)$total_paid = $transactions->where('transaction_type', 'repayment')->sum('amount'),
	// 		'total_balance' => (float)$total_balance = ((object)$breakdown_statistics)->total_repayment_amount - $total_paid,
	// 		'next_repayment_due_date' => $due_date = ($transactions->sortByDesc('id')->values()->first())->next_installment_due_date->toDateString(),
	// 		'loan_defaulter' => (boolean)$total_balance == 0 ? false : now()->gte($due_date)
	// 	])->merge($breakdown_statistics)->merge((new LoanTransactionTransformer)->collectionTransformer($transactions, 'transformForUserViewLoanTransactions'));
	// }
}
