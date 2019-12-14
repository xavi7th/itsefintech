<?php

namespace App\Modules\CardAdmin\Models;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\Admin\Models\ActivityLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminUserTransformer;

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

	static function routes()
	{
		Route::get('card-admins', function () {
			return (new AdminUserTransformer)->collectionTransformer(CardAdmin::withTrashed()->get(), 'transformForAdminViewCardAdmins');
		})->middleware('auth:admin');

		Route::post('card-admin/create', function () {
			// return request()->all();
			try {
				DB::beginTransaction();
				$card_admin = CardAdmin::create(Arr::collapse([
					request()->all(),
					[
						'password' => bcrypt('itsefintech@card_admin'),
					]
				]));

				DB::commit();
				return response()->json(['rsp' => $card_admin], 201);
			} catch (Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::get('card-admin/{card_admin}/permissions', function (CardAdmin $card_admin) {
			$permitted_routes = $card_admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
				return $item->id;
			});

			$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
				return ['id' => $item->id, 'description' => $item->description];
			});

			return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
		})->middleware('auth:admin');

		Route::put('card-admin/{card_admin}/permissions', function (CardAdmin $card_admin) {
			$card_admin->api_routes()->sync(request('permitted_routes'));
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('card-admin/{card_admin}/suspend', function (CardAdmin $card_admin) {
			$card_admin->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('card-admin/{id}/restore', function ($id) {
			CardAdmin::withTrashed()->find($id)->restore();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::delete('card-admin/{card_admin}/delete', function (CardAdmin $card_admin) {
			$card_admin->forceDelete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
