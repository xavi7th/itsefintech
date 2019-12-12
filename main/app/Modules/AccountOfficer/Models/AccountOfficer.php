<?php

namespace App\Modules\AccountOfficer\Models;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminUserTransformer;

class AccountOfficer extends User
{
	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'account-officers';

	static function canAccess()
	{
		return Auth::guard('account_officer')->check();
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
		static::deleting(function (AccountOfficer $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}

	static function routes()
	{
		Route::get('account-officers', function () {
			return (new AdminUserTransformer)->collectionTransformer(AccountOfficer::withTrashed()->get(), 'transformForAdminViewAccountOfficers');
		})->middleware('auth:admin');

		Route::post('account-officer/create', function () {
			// return request()->all();
			try {
				DB::beginTransaction();
				$account_officer = AccountOfficer::create(Arr::collapse([
					request()->all(),
					[
						'password' => bcrypt('itsefintech@account_officer'),
					]
				]));

				DB::commit();
				return response()->json(['rsp' => $account_officer], 201);
			} catch (Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::get('account-officer/{account_officer}/permissions', function (AccountOfficer $account_officer) {
			$permitted_routes = $account_officer->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
				return $item->id;
			});

			$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
				return ['id' => $item->id, 'description' => $item->description];
			});

			return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
		})->middleware('auth:admin');

		Route::put('account-officer/{account_officer}/permissions', function (AccountOfficer $account_officer) {
			$account_officer->api_routes()->sync(request('permitted_routes'));
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('account-officer/{account_officer}/suspend', function (AccountOfficer $account_officer) {
			$account_officer->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('account-officer/{id}/restore', function ($id) {
			AccountOfficer::withTrashed()->find($id)->restore();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::delete('account-officer/{account_officer}/delete', function (AccountOfficer $account_officer) {
			$account_officer->forceDelete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
