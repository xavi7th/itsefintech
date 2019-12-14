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

	static function routes()
	{
		Route::get('normal-admins', function () {
			return (new AdminUserTransformer)->collectionTransformer(NormalAdmin::withTrashed()->get(), 'transformForAdminViewNormalAdmins');
		})->middleware('auth:admin');

		Route::post('normal-admin/create', function () {
			// return request()->all();
			try {
				DB::beginTransaction();
				$admin = NormalAdmin::create(Arr::collapse([
					request()->all(),
					[
						'password' => bcrypt('itsefintech@admin'),
					]
				]));

				DB::commit();
				return response()->json(['rsp' => $admin], 201);
			} catch (\Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::get('normal-admin/{admin}/permissions', function (NormalAdmin $admin) {
			$permitted_routes = $admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
				return $item->id;
			});

			$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
				return ['id' => $item->id, 'description' => $item->description];
			});

			return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
		})->middleware('auth:admin');

		Route::put('normal-admin/{admin}/permissions', function (NormalAdmin $admin) {
			$admin->api_routes()->sync(request('permitted_routes'));
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('normal-admin/{admin}/suspend', function (NormalAdmin $admin) {
			if ($admin->id === auth()->id()) {
				return response()->json(['rsp' => false], 403);
			}
			$admin->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('normal-admin/{id}/restore', function ($id) {
			NormalAdmin::withTrashed()->find($id)->restore();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::delete('normal-admin/{admin}/delete', function (NormalAdmin $admin) {
			if ($admin->id === auth()->id()) {
				return response()->json(['rsp' => false], 403);
			}
			$admin->forceDelete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
