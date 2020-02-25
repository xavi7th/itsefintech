<?php

namespace App\Modules\Admin\Transformers;

use App\User;
use App\ErrLog;
use Illuminate\Support\Facades\Log;
use App\Modules\AppUser\Models\Transaction;
use App\Modules\Admin\Models\ActivityLog;

class AdminActivityLogTransformer
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
				'activities' => $collection->map(function ($v) use ($transformerMethod) {
					return $this->$transformerMethod($v);
				})
			];
		} catch (\Throwable $e) {
			return [
				'activities' => $collection->map(function ($v) use ($transformerMethod) {
					return $this->$transformerMethod($v);
				})
			];
		}
	}

	public function basicTransform(ActivityLog $log)
	{

		return [
			'id' => (int)$log->id,
			'activity' => (string)$log->activity,
			'time' => (string)$log->created_at,
		];
	}
}
