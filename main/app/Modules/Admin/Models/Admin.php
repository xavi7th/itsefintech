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

class Admin extends User
{

	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $table = "admins";
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
		return $this->morphMany(ActivityLog::class, 'user');
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

			ActivityLog::logAdminActivity(auth()->user()->email . ' created a new admin account for ' . $admin->email);

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

		ActivityLog::logAdminActivity(auth()->user()->email . ' edited the route permissions for ' . $admin->email);

		return response()->json(['rsp' => true], 204);
	}

	public function suspendAdminAccount(Admin $admin)
	{
		if ($admin->id === auth()->id()) {
			return response()->json(['rsp' => false], 403);
		}
		$admin->delete();
		ActivityLog::logAdminActivity(auth()->user()->email . ' suspended the account of ' . $admin->email);
		return response()->json(['rsp' => true], 204);
	}

	public function restoreAdminAccount($id)
	{
		$admin = Admin::withTrashed()->find($id);
		$admin->restore();

		ActivityLog::logAdminActivity(auth()->user()->email . ' restored the account of ' . $admin->email);

		return response()->json(['rsp' => true], 204);
	}

	public function deleteAdminAccount(Admin $admin)
	{
		if ($admin->id === auth()->id()) {
			return response()->json(['rsp' => false], 403);
		}
		/** log the activity before deleting */
		ActivityLog::logAdminActivity(auth()->user()->email . ' permanently deleted the account of ' . $admin->email);

		$admin->forceDelete();

		return response()->json(['rsp' => true], 204);
	}
}
