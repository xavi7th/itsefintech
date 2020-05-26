<?php

namespace App\Modules\Admin\Models;

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
 * App\Modules\Admin\Models\Admin
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Admin\Models\Admin onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereBvn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereUserPassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Admin whereVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Admin\Models\Admin withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Admin\Models\Admin withoutTrashed()
 * @mixin \Eloquent
 */
class Admin extends User
{

	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $table = "hardmean";
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'admin-panel';

	static function canAccess()
	{
		return Auth::guard('admin')->check();
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
		return $this->morphMany(ActivityLog::class, 'user')->latest();
	}

	protected static function boot()
	{
		parent::boot();
		static::deleting(function (Admin $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			Route::get('admins', 'Admin@getAllAdmins')->middleware('auth:admin,normal_admin');

			Route::post('admin/create', 'Admin@createAdminAccount')->middleware('auth:admin');

			Route::get('admin/{admin}/permissions', 'Admin@getAdminPermittedRoutes')->middleware('auth:admin');

			Route::put('admin/{admin}/permissions', 'Admin@editAdminPermittedRoutes')->middleware('auth:admin');

			Route::put('admin/{admin}/suspend', 'Admin@suspendAdminAccount')->middleware('auth:admin');

			Route::put('admin/{id}/restore', 'Admin@restoreAdminAccount')->middleware('auth:admin');

			Route::delete('admin/{admin}/delete', 'Admin@deleteAdminAccount')->middleware('auth:admin');
		});
	}

	public function getAllAdmins()
	{
		return (new AdminUserTransformer)->collectionTransformer(Admin::withTrashed()->get(), 'transformForAdminViewAdmins');
	}

	public function createAdminAccount()
	{
		try {
			DB::beginTransaction();
			$admin = Admin::create(Arr::collapse([
				request()->all(),
				[
					'password' => bcrypt('itsefintech@admin'),
				]
			]));

			ActivityLog::notifyAdmins(auth()->user()->email . ' created a new admin account for ' . $admin->email);

			DB::commit();
			return response()->json(['rsp' => $admin], 201);
		} catch (\Throwable $e) {
			if (app()->environment() == 'local') {
				return response()->json(['error' => $e->getMessage()], 500);
			}
			return response()->json(['rsp' => 'error occurred'], 500);
		}
	}

	public function getAdminPermittedRoutes(Admin $admin)
	{
		$permitted_routes = $admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
			return $item->id;
		});

		$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
			return ['id' => $item->id, 'description' => $item->description];
		});

		return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
	}

	public function editAdminPermittedRoutes(Admin $admin)
	{
		$admin->api_routes()->sync(request('permitted_routes'));

		ActivityLog::notifyAdmins(auth()->user()->email . ' edited the route permissions for ' . $admin->email);

		return response()->json(['rsp' => true], 204);
	}

	public function suspendAdminAccount(Admin $admin)
	{
		if ($admin->id === auth()->id()) {
			return response()->json(['rsp' => false], 403);
		}
		$admin->delete();
		ActivityLog::notifyAdmins(auth()->user()->email . ' suspended the account of ' . $admin->email);
		return response()->json(['rsp' => true], 204);
	}

	public function restoreAdminAccount($id)
	{
		$admin = Admin::withTrashed()->find($id);
		$admin->restore();

		ActivityLog::notifyAdmins(auth()->user()->email . ' restored the account of ' . $admin->email);

		return response()->json(['rsp' => true], 204);
	}

	public function deleteAdminAccount(Admin $admin)
	{
		if ($admin->id === auth()->id()) {
			return response()->json(['rsp' => false], 403);
		}
		/** log the activity before deleting */
		ActivityLog::notifyAdmins(auth()->user()->email . ' permanently deleted the account of ' . $admin->email);

		$admin->forceDelete();

		return response()->json(['rsp' => true], 204);
	}
}
