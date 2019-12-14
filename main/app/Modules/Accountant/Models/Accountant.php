<?php

namespace App\Modules\Accountant\Models;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\Admin\Models\ActivityLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminUserTransformer;

class Accountant extends User
{
	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'accountant';

	static function canAccess()
	{
		return Auth::guard('accountant')->check();
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
		static::deleting(function (Accountant $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}

	static function routes()
	{
		Route::get('accountants', function () {
			return (new AdminUserTransformer)->collectionTransformer(Accountant::withTrashed()->get(), 'transformForAdminViewAccountants');
		})->middleware('auth:admin');

		Route::post('accountant/create', function () {
			// return request()->all();
			try {
				DB::beginTransaction();
				$accountant = Accountant::create(Arr::collapse([
					request()->all(),
					[
						'password' => bcrypt('itsefintech@accountant'),
					]
				]));

				DB::commit();
				return response()->json(['rsp' => $accountant], 201);
			} catch (Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::get('accountant/{accountant}/permissions', function (Accountant $accountant) {
			$permitted_routes = $accountant->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
				return $item->id;
			});

			$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
				return ['id' => $item->id, 'description' => $item->description];
			});

			return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
		})->middleware('auth:admin');

		Route::put('accountant/{accountant}/permissions', function (Accountant $accountant) {
			$accountant->api_routes()->sync(request('permitted_routes'));
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('accountant/{accountant}/suspend', function (Accountant $accountant) {
			$accountant->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('accountant/{id}/restore', function ($id) {
			Accountant::withTrashed()->find($id)->restore();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::delete('accountant/{accountant}/delete', function (Accountant $accountant) {
			$accountant->forceDelete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
