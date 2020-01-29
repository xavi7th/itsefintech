<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use App\Modules\CardUser\Transformers\CardUserNotificationTransformer;

class Notification extends DatabaseNotification
{
	protected $fillable = [];

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			// Route::get('loan-requests/{admin?}', 'Notification@showAllNotifications')->middleware('auth:admin');
		});
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('notifications', 'Notification@getUserNotifications')->middleware('auth:card_user');
		});
	}


	/**
	 * ! Card User Route Methods
	 */

	public function getUserNotifications()
	{
		return (new CardUserNotificationTransformer)->collectionTransformer(auth()->user()->unreadNotifications->take(30), 'transformForCardUserListNotifications');
	}
}
