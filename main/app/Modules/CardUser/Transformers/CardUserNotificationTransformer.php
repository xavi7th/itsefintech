<?php

namespace App\Modules\CardUser\Transformers;

use Illuminate\Notifications\DatabaseNotification;


class CardUserNotificationTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		return [
			'notifications' => $collection->map(function ($v) use ($transformerMethod) {
				return $this->$transformerMethod($v);
			})
		];
	}

	public function transformForCardUserListNotifications(DatabaseNotification $notification)
	{
		return collect([
			'id' => (string)$notification->id,
			'notification' => (string)$notification->data['action'],
			'is_read' => (boolean)$notification->read_at,
			'date' => $notification->created_at->diffForHumans()
		]);
	}
}
