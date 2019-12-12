<?php

namespace App\Modules\DispatchAdmin\Models;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminUserTransformer;

class DispatchAdmin extends User
{
	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'dispatch-admins';

	static function canAccess()
	{
		return Auth::guard('dispatch_admin')->check();
	}

	public function is_verified()
	{
		return $this->verified_at !== null;
	}

	public function api_routes()
	{
		return $this->morphToMany(ApiRoute::class, 'user', 'api_routes_permissions', 'user_id');
	}

	protected static function boot()
	{
		parent::boot();
		static::deleting(function (DispatchAdmin $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}

	static function routes()
	{
		Route::get('dispatch-admins', function () {
			return (new AdminUserTransformer)->collectionTransformer(DispatchAdmin::withTrashed()->get(), 'transformForAdminViewDispatchAdmins');
		})->middleware('auth:admin');

		Route::post('dispatch-admin/create', function () {
			// return request()->all();
			try {
				DB::beginTransaction();
				$dispatch_admin = DispatchAdmin::create(Arr::collapse([
					request()->all(),
					[
						'password' => bcrypt('itsefintech@dispatch_admin'),
					]
				]));

				DB::commit();
				return response()->json(['rsp' => $dispatch_admin], 201);
			} catch (Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::get('dispatch-admin/{dispatch_admin}/permissions', function (DispatchAdmin $dispatch_admin) {
			$permitted_routes = $dispatch_admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
				return $item->id;
			});

			$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
				return ['id' => $item->id, 'description' => $item->description];
			});

			return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
		})->middleware('auth:admin');

		Route::put('dispatch-admin/{dispatch_admin}/permissions', function (DispatchAdmin $dispatch_admin) {
			$dispatch_admin->api_routes()->sync(request('permitted_routes'));
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('dispatch-admin/{dispatch_admin}/suspend', function (DispatchAdmin $dispatch_admin) {
			$dispatch_admin->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('dispatch-admin/{id}/restore', function ($id) {
			DispatchAdmin::withTrashed()->find($id)->restore();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::delete('dispatch-admin/{dispatch_admin}/delete', function (DispatchAdmin $dispatch_admin) {
			$dispatch_admin->forceDelete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
