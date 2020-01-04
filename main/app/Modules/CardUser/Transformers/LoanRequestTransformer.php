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

	public function transformForLoanRequest(LoanRequest $loan_request)
	{
		$curr = (function () use ($loan_request) {
			switch ($loan_request->currency) {
				case 'USD':
					return '$';
					break;
				case 'GBP':
					return '£';
					break;
				case 'EUR':
					return '€';
					break;

				default:
					return $loan_request->currency;
					break;
			}
		})();
		return [
			'id' => (int)$loan_request->id,
			'name' => (string)$loan_request->name,
			'email' => (string)$loan_request->email,
			'country' => (string)$loan_request->country,
			'currency' => (string)$curr,
			'phone' => (string)$loan_request->phone,
			'id_card' => (string)$loan_request->id_card,
			// 'is_verified' => (bool)$loan_request->is_verified(),
			'total_deposit' => (double)$loan_request->total_deposit_amount(),
			'total_withdrawal' => (double)$loan_request->total_withdrawal_amount(),
			'total_profit' => (double)$loan_request->total_profit_amount(),
			'target_profit' => (double)$loan_request->expected_withdrawal_amount(),
			'total_withdrawable' => (double)number_format($loan_request->total_withdrawalable_amount(), 2, '.', '')
		];
	}

	public function transformWithdrawalRequest(WithdrawalRequest $wthReq)
	{

		return [
			'id' => (int)$wthReq->id,
			'amount' => (double)$wthReq->amount,
			'payment_option' => (string)$wthReq->payment_option,
			'bitcoin_acc' => (string)$wthReq->bitcoin_acc,
			'receiver_name' => (string)$wthReq->receiver_name,
			'secret_question' => (string)$wthReq->secret_question,
			'secret_answer' => (string)$wthReq->secret_answer,
			'id_type' => (string)$wthReq->id_type,
			'country' => (string)$wthReq->country,
			'acc_name' => (string)$wthReq->acc_name,
			'acc_num' => (string)$wthReq->acc_num,
			'acc_bank' => (string)$wthReq->acc_bank,
			'created_at' => (string)$wthReq->created_at->diffForHumans()
		];
	}

	public function transformProfitTransaction(Transaction $profit)
	{

		return [
			'id' => (int)$profit->id,
			'amount' => (double)$profit->amount,
			'date' => (string)$profit->trans_date->diffForHumans()
		];
	}
	public function transformForProfitChart(LoanRequest $loan_request)
	{
		$deposits = $loan_request->deposit_transactions()->oldest('trans_date')->get();
		$profits = $loan_request->profit_transactions()->oldest('trans_date')->get();
		$transactions = ($deposits->concat($profits))->sortBy('trans_date')->values();
		// dd($transactions->toArray());

		return $transactions = $transactions->map(function ($item, $key) {
			return [
				'date' => $item->trans_type . ', ' . $item->trans_date->toFormattedDateString(),
				'amount' => $item->amount,
			];
		});

		// return [
		// 	$transactions
		// ];
	}
}
