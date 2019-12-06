<?php

namespace App\Modules\Admin\Transformers;

use App\User;
use Illuminate\Support\Facades\Log;
use App\Modules\AppUser\Models\Transaction;
use App\Modules\AppUser\Models\WithdrawalRequest;

class AdminTransactionTransformer
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

	public function transformForAdminViewTransactions(Transaction $transaction)
	{
		return [
			'id' => (int)$transaction->id,
			'trans_date' => (string)$transaction->trans_date->toDateString(),
			'amount' => (string)'$' . number_format($transaction->amount, 2),
			'investment_plan' => (string)$transaction->investment_plan,
			'trans_type' => (string)$transaction->trans_type,
			// 'user' => (new AdminUserTransformer)->transform($user)
		];
	}

	public function transformForAdminViewLatestWithdrawalRequestsSummary(WithdrawalRequest $request)
	{
		return [
			// 'id' => (int)$transaction->id,
			// 'name' => (string)$transaction->user->email,
			// 'text' => (string)' requested ' . $transaction->amount . ' on ' . $transaction->trans_date->toDateString(),
			// 'photoURL' => null
			'id' => (int)$request->id,
			'name' => (string)$request->app_user->email,
			'text' => (string)' requested $' . number_format($request->amount, 2) . ' on ' . $request->created_at->toDateString(),
			'photoURL' => null
		];
	}
}
