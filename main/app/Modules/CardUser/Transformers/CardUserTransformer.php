<?php

namespace App\Modules\CardUser\Transformers;

use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\Transaction;
use App\Modules\CardUser\Models\WithdrawalRequest;

class CardUserTransformer
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

	public function transform(CardUser $user)
	{
		return [
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'email' => $user->email,
			'phone' => $user->phone,
			'bvn' => $user->bvn,
			'card_user_category' => (string)$user->card_user_category->category_name,
			'assigned_credit_limit' => (float)$user->assigned_credit_limit,
			'assigned_merchant_limit' => (float)$user->merchant_limit,
			'due_for_credit' => (boolean)$due_for_credit = $user->due_for_credit(),
			'num_of_days_active' => (int)$user->activeDays()

		];
	}

	public function transformForCardUser(CardUser $user)
	{
		$curr = (function () use ($user) {
			switch ($user->currency) {
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
					return $user->currency;
					break;
			}
		})();
		return [
			'id' => (int)$user->id,
			'name' => (string)$user->name,
			'email' => (string)$user->email,
			'country' => (string)$user->country,
			'currency' => (string)$curr,
			'phone' => (string)$user->phone,
			'id_card' => (string)$user->id_card,
			// 'is_verified' => (bool)$user->is_verified(),
			'total_deposit' => (double)$user->total_deposit_amount(),
			'total_withdrawal' => (double)$user->total_withdrawal_amount(),
			'total_profit' => (double)$user->total_profit_amount(),
			'target_profit' => (double)$user->expected_withdrawal_amount(),
			'total_withdrawable' => (double)number_format($user->total_withdrawalable_amount(), 2, '.', '')
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
	public function transformForProfitChart(CardUser $user)
	{
		$deposits = $user->deposit_transactions()->oldest('trans_date')->get();
		$profits = $user->profit_transactions()->oldest('trans_date')->get();
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
