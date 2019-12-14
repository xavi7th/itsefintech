<?php

namespace App\Modules\SalesRep\Models;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminUserTransformer;

class SalesRep extends User
{
	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'sales-reps';

	static function canAccess()
	{
		return Auth::guard('sales_rep')->check();
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

	public function assigned_debit_cards()
	{
		return $this->hasMany(DebitCard::class);
	}

	protected static function boot()
	{
		parent::boot();
		static::deleting(function (SalesRep $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}

	static function routes()
	{


		Route::get('sales-reps', function () {
			return (new AdminUserTransformer)->collectionTransformer(SalesRep::withTrashed()->get(), 'transformForAdminViewSalesReps');
		})->middleware('auth:admin');

		Route::post('sales-rep/create', function () {
			// return request()->all();
			try {
				DB::beginTransaction();
				$sales_rep = SalesRep::create(Arr::collapse([
					request()->all(),
					[
						'password' => bcrypt('itsefintech@sales_rep'),
					]
				]));

				DB::commit();
				return response()->json(['rsp' => $sales_rep], 201);
			} catch (Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::get('sales-rep/{sales_rep}/permissions', function (SalesRep $sales_rep) {
			$permitted_routes = $sales_rep->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
				return $item->id;
			});

			$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
				return ['id' => $item->id, 'description' => $item->description];
			});

			return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
		})->middleware('auth:admin');

		Route::put('sales-rep/{sales_rep}/permissions', function (SalesRep $sales_rep) {
			$sales_rep->api_routes()->sync(request('permitted_routes'));
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('sales-rep/{sales_rep}/suspend', function (SalesRep $sales_rep) {
			$sales_rep->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('sales-rep/{id}/restore', function ($id) {
			SalesRep::withTrashed()->find($id)->restore();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::delete('sales-rep/{sales_rep}/delete', function (SalesRep $sales_rep) {
			$sales_rep->forceDelete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
