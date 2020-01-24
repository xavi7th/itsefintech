<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\CardUser\Models\DebitCardFundingRequest;

class DebitCardFundingRequestTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'data' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transform(DebitCardFundingRequest $funding_request)
	{
		return [
			// 'id' => (int)$funding_request->id,
			'amount' => (double)$funding_request->amount,
			'card' => (int)$funding_request->debit_card->card_number,
			'is_processed' => (boolean)$funding_request->is_funded,
		];
	}

	// public function transformWithLoanTransactions(DebitCardFundingRequest $funding_request)
	// {
	// 	$transactions = $funding_request->loan_transactions;
	// 	$breakdown_statistics = $funding_request->breakdownStatistics();
	// 	return collect([
	// 		'id' => $funding_request->id,
	// 		// 'amount' => $funding_request->amount,
	// 		'is_approved' => (boolean)$funding_request->approved_at,
	// 		'request_date' => $funding_request->created_at->toDateString(),
	// 		'total_paid' => (float)$total_paid = $transactions->where('transaction_type', 'repayment')->sum('amount'),
	// 		'total_balance' => (float)$total_balance = ((object)$breakdown_statistics)->total_repayment_amount - $total_paid,
	// 		'next_repayment_due_date' => $due_date = ($transactions->sortByDesc('id')->values()->first())->next_installment_due_date->toDateString(),
	// 		'loan_defaulter' => (boolean)$total_balance == 0 ? false : now()->gte($due_date)
	// 	])->merge($breakdown_statistics)->merge((new LoanTransactionTransformer)->collectionTransformer($transactions, 'transformForUserViewLoanTransactions'));
	// }
}
