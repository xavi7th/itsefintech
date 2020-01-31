<?php

namespace App\Modules\NormalAdmin\Models;


use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\Admin\Models\ActivityLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminUserTransformer;

class NormalAdmin extends User
{

	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $table = "normal_admins";
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'backend';

	static function canAccess()
	{
		return Auth::guard('normal_admin')->check();
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
		static::deleting(function (NormalAdmin $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\NormalAdmin\Models'], function () {
			Route::get('normal-admins', 'NormalAdmin@getAllNormalAdmins')->middleware('auth:admin');

			Route::post('normal-admin/create', 'NormalAdmin@createNormalAdmin')->middleware('auth:admin');

			Route::get('normal-admin/{admin}/permissions', 'NormalAdmin@getNormalAdminRoutePermissions')->middleware('auth:admin');

			Route::put('normal-admin/{admin}/permissions', 'NormalAdmin@editNormalAdminRoutePermissions')->middleware('auth:admin');

			Route::put('normal-admin/{admin}/suspend', 'NormalAdmin@suspendNormalAdmin')->middleware('auth:admin');

			Route::put('normal-admin/{id}/restore', 'NormalAdmin@restoreNormalAdmin')->middleware('auth:admin');

			Route::delete('normal-admin/{admin}/delete', 'NormalAdmin@deleteNormalAdminAccount')->middleware('auth:admin');
		});
	}

	static function normalAdminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\NormalAdmin\Models'], function () {

			Route::group(['prefix' => 'api'], function () {
				Route::post('test-route-permission', 'NormalAdmin@testRoutePermissions');
			});

			Route::get('/{subcat?}', 'NormalAdmin@loadNormalAdminApplication')->name('normaladmin.dashboard')->where('subcat', '^((?!(api)).)*');
		});
	}


	public function testRoutePermissions()
	{
		$api_route = ApiRoute::where('name', request('route'))->first();
		if ($api_route) {
			return ['rsp'  => $api_route->normal_admins()->where('user_id', auth()->id())->exists()];
		} else {
			return response()->json(['rsp' => false], 410);
		}
	}

	public function loadNormalAdminApplication()
	{
		return view('normaladmin::index');
	}

	public function getAllNormalAdmins()
	{
		return (new AdminUserTransformer)->collectionTransformer(NormalAdmin::withTrashed()->get(), 'transformForAdminViewNormalAdmins');
	}

	public function createNormalAdmin()
	{
		try {
			DB::beginTransaction();
			$admin = NormalAdmin::create(Arr::collapse([
				request()->all(),
				[
					'password' => bcrypt('itsefintech@admin'),
				]
			]));

			DB::commit();

			ActivityLog::logAdminActivity(auth()->user()->email . ' created a normal admin account for ' . $admin->email);

			return response()->json(['rsp' => $admin], 201);
		} catch (\Throwable $e) {
			if (app()->environment() == 'local') {
				return response()->json(['error' => $e->getMessage()], 500);
			}
			return response()->json(['rsp' => 'error occurred'], 500);
		}
	}

	public function getNormalAdminRoutePermissions(NormalAdmin $admin)
	{
		$permitted_routes = $admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
			return $item->id;
		});

		$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
			return ['id' => $item->id, 'description' => $item->description];
		});

		return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
	}

	public function editNormalAdminRoutePermissions(NormalAdmin $admin)
	{
		$admin->api_routes()->sync(request('permitted_routes'));

		ActivityLog::logAdminActivity(auth()->user()->email . ' edited account permissions for ' . $admin->email);

		return response()->json(['rsp' => true], 204);
	}

	public function suspendNormalAdmin(NormalAdmin $admin)
	{
		if ($admin->id === auth()->id()) {
			return response()->json(['rsp' => false], 403);
		}
		$admin->delete();

		ActivityLog::logAdminActivity(auth()->user()->email . ' suspended the normal admin account for ' . $admin->email);

		return response()->json(['rsp' => true], 204);
	}

	public function restoreNormalAdmin($id)
	{
		$admin = NormalAdmin::withTrashed()->find($id);
		$admin->restore();

		ActivityLog::logAdminActivity(auth()->user()->email . ' restored the normal admin account for ' . $admin->email);

		return response()->json(['rsp' => true], 204);
	}

	public function deleteNormalAdminAccount(NormalAdmin $admin)
	{
		if ($admin->id === auth()->id()) {
			return response()->json(['rsp' => false], 403);
		}

		ActivityLog::logAdminActivity(auth()->user()->email . ' permanently deleted the normal admin account for ' . $admin->email);

		$admin->forceDelete();
		return response()->json(['rsp' => true], 204);
	}
}
