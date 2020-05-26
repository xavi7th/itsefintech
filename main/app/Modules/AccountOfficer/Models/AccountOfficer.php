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
use App\Modules\Admin\Models\ActivityLog;

/**
 * App\Modules\AccountOfficer\Models\AccountOfficer
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string|null $phone
 * @property string|null $bvn
 * @property string|null $user_passport
 * @property string|null $gender
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $dob
 * @property string|null $verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Admin\Models\ActivityLog[] $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Admin\Models\ApiRoute[] $api_routes
 * @property-read int|null $api_routes_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereBvn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereUserPassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer whereVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\AccountOfficer\Models\AccountOfficer withoutTrashed()
 * @mixin \Eloquent
 */
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

	public function activities()
	{
		return $this->morphMany(ActivityLog::class, 'user');
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

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\AccountOfficer\Models'], function () {
			Route::get('account-officers', 'AccountOfficer@getAllAccountOfficers')->middleware('auth:admin');

			Route::post('account-officer/create', 'AccountOfficer@createAccountOfficer')->middleware('auth:admin');

			Route::get('account-officer/{account_officer}/permissions', 'AccountOfficer@getAccountOfficerPermissions')->middleware('auth:admin');

			Route::put('account-officer/{account_officer}/permissions', 'AccountOfficer@editAccountOfficerPermissions')->middleware('auth:admin');

			Route::put('account-officer/{account_officer}/suspend', 'AccountOfficer@suspendAccountOfficer')->middleware('auth:admin');

			Route::put('account-officer/{id}/restore', 'AccountOfficer@restoreAccountOfficer')->middleware('auth:admin');

			Route::delete('account-officer/{account_officer}/delete', 'AccountOfficer@deleteAccountOfficer')->middleware('auth:admin');
		});
	}

	static function accountOfficerRoutes()
	{
		Route::group(['middleware' => ['auth:account_officer', 'account_officers'], 'namespace' => '\App\Modules\AccountOfficer\Models'], function () {
			Route::group(['prefix' => 'api'], function () {

				Route::post('test-route-permission', 'AccountOfficer@testRoutePermission');
			});

			Route::get('/{subcat?}', 'AccountOfficer@loadAccountOfficerApplication')->name('accountofficer.dashboard')->where('subcat', '^((?!(api)).)*');
		});
	}

	public function loadAccountOfficerApplication()
	{
		return view('accountofficer::index');
	}

	public function testRoutePermission()
	{
		$api_route = ApiRoute::where('name', request('route'))->first();
		if ($api_route) {
			return ['rsp'  => $api_route->account_officers()->where('user_id', auth('account_officer')->id())->exists()];
		} else {
			return response()->json(['rsp' => false], 410);
		}
	}

	public function getAllAccountOfficers()
	{
		return (new AdminUserTransformer)->collectionTransformer(AccountOfficer::withTrashed()->get(), 'transformForAdminViewAccountOfficers');
	}

	public function createAccountOfficer()
	{
		try {
			DB::beginTransaction();
			$account_officer = AccountOfficer::create(Arr::collapse([
				request()->all(),
				[
					'password' => bcrypt('itsefintech@account_officer'),
				]
			]));

			DB::commit();

			ActivityLog::notifyAdmins(auth()->user()->email . ' created an account officer account for ' . $account_officer->email);

			return response()->json(['rsp' => $account_officer], 201);
		} catch (Throwable $e) {
			if (app()->environment() == 'local') {
				return response()->json(['error' => $e->getMessage()], 500);
			}
			return response()->json(['rsp' => 'error occurred'], 500);
		}
	}

	public function getAccountOfficerPermissions(AccountOfficer $account_officer)
	{
		$permitted_routes = $account_officer->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
			return $item->id;
		});

		$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
			return ['id' => $item->id, 'description' => $item->description];
		});

		return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
	}

	public function editAccountOfficerPermissions(AccountOfficer $account_officer)
	{
		$account_officer->api_routes()->sync(request('permitted_routes'));

		ActivityLog::notifyAdmins(auth()->user()->email . ' edited the account permissions for ' . $account_officer->email);

		return response()->json(['rsp' => true], 204);
	}

	public function suspendAccountOfficer(AccountOfficer $account_officer)
	{
		ActivityLog::notifyAdmins(auth()->user()->email . ' suspended the account of ' . $account_officer->email);

		$account_officer->delete();

		return response()->json(['rsp' => true], 204);
	}

	public function restoreAccountOfficer($id)
	{
		$account_officer = AccountOfficer::withTrashed()->find($id);

		$account_officer->restore();

		ActivityLog::notifyAdmins(auth()->user()->email . ' restored the account of ' . $account_officer->email);

		return response()->json(['rsp' => true], 204);
	}

	public function deleteAccountOfficer(AccountOfficer $account_officer)
	{
		ActivityLog::notifyAdmins(auth()->user()->email . ' permanently deleted the account of ' . $account_officer->email);

		$account_officer->forceDelete();

		return response()->json(['rsp' => true], 204);
	}
}
