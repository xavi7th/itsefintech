<?php

namespace App\Modules\CustomerSupport\Models;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminUserTransformer;

class CustomerSupport extends User
{
	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'customer-supports';

	static function canAccess()
	{
		return Auth::guard('customer_support')->check();
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
		static::deleting(function (CustomerSupport $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}

	static function routes()
	{
		Route::get('customer-supports', function () {
			return (new AdminUserTransformer)->collectionTransformer(CustomerSupport::withTrashed()->get(), 'transformForAdminViewCustomerSupports');
		})->middleware('auth:admin');

		Route::post('customer-support/create', function () {
			// return request()->all();
			try {
				DB::beginTransaction();
				$customer_support = CustomerSupport::create(Arr::collapse([
					request()->all(),
					[
						'password' => bcrypt('itsefintech@customer_support'),
					]
				]));

				DB::commit();
				return response()->json(['rsp' => $customer_support], 201);
			} catch (Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::get('customer-support/{customer_support}/permissions', function (CustomerSupport $customer_support) {
			$permitted_routes = $customer_support->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
				return $item->id;
			});

			$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
				return ['id' => $item->id, 'description' => $item->description];
			});

			return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
		})->middleware('auth:admin');

		Route::put('customer-support/{customer_support}/permissions', function (CustomerSupport $customer_support) {
			$customer_support->api_routes()->sync(request('permitted_routes'));
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('customer-support/{customer_support}/suspend', function (CustomerSupport $customer_support) {
			$customer_support->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('customer-support/{id}/restore', function ($id) {
			CustomerSupport::withTrashed()->find($id)->restore();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::delete('customer-support/{customer_support}/delete', function (CustomerSupport $customer_support) {
			$customer_support->forceDelete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
