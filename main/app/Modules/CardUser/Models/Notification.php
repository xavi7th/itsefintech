<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use App\Modules\CardUser\Transformers\CardUserNotificationTransformer;

/**
 * App\Modules\CardUser\Models\Notification
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
		Route::group(['namespace' => '\App\Modules\CardUser\Models', 'middleware' => ['verified_card_users']], function () {
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
