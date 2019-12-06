<?php

namespace App\Modules\Admin\Transformers;

use App\User;
use App\ErrLog;
use Illuminate\Support\Facades\Log;
use App\Modules\AppUser\Models\Transaction;

class AdminActivityTransformer
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

	public function transformForAdminViewLatestActivitiesSummary(ErrLog $log)
	{
		$arr = explode(' ', $log->message, 2);
		if (str_contains($arr[0], '@')) {
			return [
				'id' => (int)$log->id,
				'name' => (string)$arr[0],
				'text' =>  (string)$arr[1] . ' ' . $log->created_at->diffForHumans() . ' at ' . $log->created_at->toTimeString(),
				'photoURL' => false
			];
		}
	}
}
