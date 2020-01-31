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

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Accountant\Models'], function () {
			Route::get('accountants', 'Accountant@getAllAccountants')->middleware('auth:admin');

			Route::post('accountant/create', 'Accountant@createAccountant')->middleware('auth:admin');

			Route::get('accountant/{accountant}/permissions', 'Accountant@getAccountantPermissions')->middleware('auth:admin');

			Route::put('accountant/{accountant}/permissions', 'Accountant@editAccountantPermissions')->middleware('auth:admin');

			Route::put('accountant/{accountant}/suspend', 'Accountant@suspendAccountant')->middleware('auth:admin');

			Route::put('accountant/{id}/restore', 'Accountant@restoreAccountant')->middleware('auth:admin');

			Route::delete('accountant/{accountant}/delete', 'Accountant@deleteAccountant')->middleware('auth:admin');
		});
	}


	public function getAllAccountants()
	{
		return (new AdminUserTransformer)->collectionTransformer(Accountant::withTrashed()->get(), 'transformForAdminViewAccountants');
	}

	public function createAccountant()
	{
		try {
			DB::beginTransaction();
			$accountant = Accountant::create(Arr::collapse([
				request()->all(),
				[
					'password' => bcrypt('itsefintech@accountant'),
				]
			]));

			DB::commit();

			ActivityLog::logAdminActivity(auth()->user()->email . ' created an accountant account for ' . $accountant->email);

			return response()->json(['rsp' => $accountant], 201);
		} catch (Throwable $e) {
			if (app()->environment() == 'local') {
				return response()->json(['error' => $e->getMessage()], 500);
			}
			return response()->json(['rsp' => 'error occurred'], 500);
		}
	}

	public function getAccountantPermissions(Accountant $accountant)
	{
		$permitted_routes = $accountant->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
			return $item->id;
		});

		$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
			return ['id' => $item->id, 'description' => $item->description];
		});

		return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
	}

	public function editAccountantPermissions(Accountant $accountant)
	{
		$accountant->api_routes()->sync(request('permitted_routes'));

		ActivityLog::logAdminActivity(auth()->user()->email . ' edited the account permissions for ' . $accountant->email);

		return response()->json(['rsp' => true], 204);
	}

	public function suspendAccountant(Accountant $accountant)
	{
		ActivityLog::logAdminActivity(auth()->user()->email . ' suspended the account of ' . $accountant->email);

		$accountant->delete();

		return response()->json(['rsp' => true], 204);
	}

	public function restoreAccountant($id)
	{
		$accountant = Accountant::withTrashed()->find($id);

		$accountant->restore();

		ActivityLog::logAdminActivity(auth()->user()->email . ' restored the account of ' . $accountant->email);

		return response()->json(['rsp' => true], 204);
	}

	public function deleteAccountant(Accountant $accountant)
	{
		ActivityLog::logAdminActivity(auth()->user()->email . ' permanently deleted the account of ' . $accountant->email);

		$accountant->forceDelete();

		return response()->json(['rsp' => true], 204);
	}
}
