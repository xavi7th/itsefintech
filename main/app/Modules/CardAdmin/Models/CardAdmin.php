<?php

namespace App\Modules\CardAdmin\Models;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\Admin\Models\ActivityLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminUserTransformer;

/**
 * App\Modules\CardAdmin\Models\CardAdmin
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string|null $phone
 * @property string|null $bvn
 * @property string|null $user_passport
 * @property string|null $gender
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $dob
 * @property string|null $verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Admin\Models\ActivityLog[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Admin\Models\ApiRoute[] $api_routes
 * @property-read int|null $api_routes_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardAdmin\Models\CardAdmin onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereBvn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereUserPassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardAdmin\Models\CardAdmin whereVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardAdmin\Models\CardAdmin withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardAdmin\Models\CardAdmin withoutTrashed()
 * @mixin \Eloquent
 */
class CardAdmin extends User
{
	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'card-admins';

	static function canAccess()
	{
		return Auth::guard('card_admin')->check();
	}

	public function is_verified()
	{
		return $this->verified_at !== null;
	}

	public function api_routes()
	{
		return $this->morphToMany(ApiRoute::class, 'user', 'api_routes_permissions', 'user_id');
	}


	public function activities()
	{
		return $this->morphMany(ActivityLog::class, 'user');
	}

	protected static function boot()
	{
		parent::boot();
		static::deleting(function (CardAdmin $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardAdmin\Models'], function () {
			Route::get('card-admins', 'CardAdmin@getAllCardAdmins')->middleware('auth:admin');

			Route::post('card-admin/create', 'CardAdmin@createCardAdmin')->middleware('auth:admin');

			Route::get('card-admin/{card_admin}/permissions', 'CardAdmin@getCardAdminPermissions')->middleware('auth:admin');

			Route::put('card-admin/{card_admin}/permissions', 'CardAdmin@editCardAdminPermissions')->middleware('auth:admin');

			Route::put('card-admin/{card_admin}/suspend', 'CardAdmin@suspendCardAdmin')->middleware('auth:admin');

			Route::put('card-admin/{id}/restore', 'CardAdmin@restoreCardAdmin')->middleware('auth:admin');

			Route::delete('card-admin/{card_admin}/delete', 'CardAdmin@deleteCardAdmin')->middleware('auth:admin');
		});
	}

	static function cardAdminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardAdmin\Models'], function () {
			Route::group(['prefix' => 'api'], function () {
				Route::post('test-route-permission', 'CardAdmin@testRoutePermission');

				Route::get('statistics', 'CardAdmin@getDashboardStatistics')->middleware('auth:card_admin');
			});


			Route::get('/{subcat?}', 'CardAdmin@loadCardAdminApplication')->name('cardadmin.dashboard')->where('subcat', '^((?!(api)).)*');
		});
	}

	public function testRoutePermission()
	{
		$api_route = ApiRoute::where('name', request('route'))->first();
		if ($api_route) {
			return ['rsp'  => $api_route->card_admins()->where('user_id', auth('card_admin')->id())->exists()];
		} else {
			return response()->json(['rsp' => false], 410);
		}
	}


	public function getDashboardStatistics()
	{
		return [
			'recent_activities' => auth()->user()->activities()->take(4)->latest()->get(),
		];
	}

	public function loadCardAdminApplication()
	{
		return view('cardadmin::index');
	}

	public function getAllCardAdmins()
	{
		return (new AdminUserTransformer)->collectionTransformer(CardAdmin::withTrashed()->get(), 'transformForAdminViewCardAdmins');
	}

	public function createCardAdmin()
	{
		try {
			DB::beginTransaction();
			$card_admin = CardAdmin::create(Arr::collapse([
				request()->all(),
				[
					'password' => bcrypt('itsefintech@card_admin'),
				]
			]));

			DB::commit();

			ActivityLog::notifyAdmins(auth()->user()->email . ' created an card admin account for ' . $card_admin->email);

			return response()->json(['rsp' => $card_admin], 201);
		} catch (Throwable $e) {
			if (app()->environment() == 'local') {
				return response()->json(['error' => $e->getMessage()], 500);
			}
			return response()->json(['rsp' => 'error occurred'], 500);
		}
	}

	public function getCardAdminPermissions(CardAdmin $card_admin)
	{
		$permitted_routes = $card_admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
			return $item->id;
		});

		$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
			return ['id' => $item->id, 'description' => $item->description];
		});

		return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
	}

	public function editCardAdminPermissions(CardAdmin $card_admin)
	{
		$card_admin->api_routes()->sync(request('permitted_routes'));

		ActivityLog::notifyAdmins(auth()->user()->email . ' edited the account permissions for ' . $card_admin->email);

		return response()->json(['rsp' => true], 204);
	}

	public function suspendCardAdmin(CardAdmin $card_admin)
	{
		ActivityLog::notifyAdmins(auth()->user()->email . ' suspended the account of ' . $card_admin->email);

		$card_admin->delete();

		return response()->json(['rsp' => true], 204);
	}

	public function restoreCardAdmin($id)
	{
		$card_admin = CardAdmin::withTrashed()->find($id);

		$card_admin->restore();

		ActivityLog::notifyAdmins(auth()->user()->email . ' restored the account of ' . $card_admin->email);

		return response()->json(['rsp' => true], 204);
	}

	public function deleteCardAdmin(CardAdmin $card_admin)
	{
		ActivityLog::notifyAdmins(auth()->user()->email . ' permanently deleted the account of ' . $card_admin->email);

		$card_admin->forceDelete();

		return response()->json(['rsp' => true], 204);
	}
}
