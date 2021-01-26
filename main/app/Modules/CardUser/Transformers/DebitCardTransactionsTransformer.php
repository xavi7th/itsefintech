<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\CardUser\Models\DebitCardTransaction;
use Carbon\Carbon;

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

	public function transform(object $cardTransaction)
	{
		return [
			'id' => (string)$cardTransaction->id,
			'amount' => (double)$cardTransaction->amount,
      'balance_after' => (float)$cardTransaction->balance_after,
      'balance_before' => (float)$cardTransaction->balance_before,
      'trans_ref' => (string)$cardTransaction->reference,
			'trans_category' => (string)$cardTransaction->category,
			'trans_description' => (string)$cardTransaction->description,
			'trans_type' => (string)$cardTransaction->type,
			'trans_date' => (string)Carbon::parse($cardTransaction->createdAt)->toFormattedDateString(),
			'trans_date_long' => (string)Carbon::parse($cardTransaction->created_at)->format('l jS \\of F Y h:i:s A')  // Thursday 25th of December 1975 02:15:16 PM,

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
