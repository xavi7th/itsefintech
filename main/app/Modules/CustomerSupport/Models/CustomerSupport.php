<?php

namespace App\Modules\CustomerSupport\Models;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\Admin\Models\ActivityLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CustomerSupport\Models\SupportTicket;
use App\Modules\Admin\Transformers\AdminUserTransformer;

/**
 * App\Modules\CustomerSupport\Models\CustomerSupport
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CustomerSupport\Models\SupportTicket[] $assigned_tickets
 * @property-read int|null $assigned_tickets_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CustomerSupport\Models\SupportTicket[] $resolved_tickets
 * @property-read int|null $resolved_tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CustomerSupport\Models\SupportTicket[] $support_tickets
 * @property-read int|null $support_tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereBvn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereUserPassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport whereVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CustomerSupport\Models\CustomerSupport withoutTrashed()
 * @mixin \Eloquent
 */
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

	public function activities()
	{
		return $this->morphMany(ActivityLog::class, 'user');
	}

	public function support_tickets()
	{
		return $this->hasMany(SupportTicket::class);
	}

	public function assigned_tickets()
	{
		return $this->morphMany(SupportTicket::class, 'asignee');
	}

	public function resolved_tickets()
	{
		return $this->morphMany(SupportTicket::class, 'resolver');
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

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CustomerSupport\Models'], function () {
			Route::get('customer-supports', 'CustomerSupport@getAllCustomerSupports')->middleware('auth:admin');

			Route::post('customer-support/create', 'CustomerSupport@createCustomerSupport')->middleware('auth:admin');

			Route::get('customer-support/{customer_support}/permissions', 'CustomerSupport@getCustomerSupportPermissions')->middleware('auth:admin');

			Route::put('customer-support/{customer_support}/permissions', 'CustomerSupport@editCustomerSupportPermissions')->middleware('auth:admin');

			Route::put('customer-support/{customer_support}/suspend', 'CustomerSupport@suspendCustomerSupport')->middleware('auth:admin');

			Route::put('customer-support/{id}/restore', 'CustomerSupport@restoreCustomerSupport')->middleware('auth:admin');

			Route::delete('customer-support/{customer_support}/delete', 'CustomerSupport@deleteCustomerSupport')->middleware('auth:admin');
		});
	}

	static function customerSupportRoutes()
	{
		Route::group(['middleware' => ['auth:customer_support', 'customer_supports'], 'namespace' => '\App\Modules\CustomerSupport\Models'], function () {
			Route::group(['prefix' => 'api'], function () {
				Route::post('test-route-permission', 'CustomerSupport@testRoutePermission');
			});

			Route::get('/{subcat?}', 'CustomerSupport@loadCustomerSupportApplication')->name('customersupport.dashboard')->where('subcat', '^((?!(api)).)*');
		});
	}

	public	function testRoutePermission()
	{
		$api_route = ApiRoute::where('name', request('route'))->first();
		if ($api_route) {
			return ['rsp'  => $api_route->customer_supports()->where('user_id', auth('customer_support')->id())->exists()];
		} else {
			return response()->json(['rsp' => false], 410);
		}
	}

	public	function loadCustomerSupportApplication()
	{
		return view('customersupport::index');
	}


	public function getAllCustomerSupports()
	{
		return (new AdminUserTransformer)->collectionTransformer(CustomerSupport::withTrashed()->get(), 'transformForAdminViewCustomerSupports');
	}

	public function createCustomerSupport()
	{
		try {
			DB::beginTransaction();
			$customer_support = CustomerSupport::create(Arr::collapse([
				request()->all(),
				[
					'password' => bcrypt('itsefintech@customer_support'),
				]
			]));

			DB::commit();

			ActivityLog::notifyAdmins(auth()->user()->email . ' created an account officer account for ' . $customer_support->email);

			return response()->json(['rsp' => $customer_support], 201);
		} catch (Throwable $e) {
			if (app()->environment() == 'local') {
				return response()->json(['error' => $e->getMessage()], 500);
			}
			return response()->json(['rsp' => 'error occurred'], 500);
		}
	}

	public function getCustomerSupportPermissions(CustomerSupport $customer_support)
	{
		$permitted_routes = $customer_support->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
			return $item->id;
		});

		$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
			return ['id' => $item->id, 'description' => $item->description];
		});

		return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
	}

	public function editCustomerSupportPermissions(CustomerSupport $customer_support)
	{
		$customer_support->api_routes()->sync(request('permitted_routes'));

		ActivityLog::notifyAdmins(auth()->user()->email . ' edited the account permissions for ' . $customer_support->email);

		return response()->json(['rsp' => true], 204);
	}

	public function suspendCustomerSupport(CustomerSupport $customer_support)
	{
		ActivityLog::notifyAdmins(auth()->user()->email . ' suspended the account of ' . $customer_support->email);

		$customer_support->delete();

		return response()->json(['rsp' => true], 204);
	}

	public function restoreCustomerSupport($id)
	{
		$customer_support = CustomerSupport::withTrashed()->find($id);

		$customer_support->restore();

		ActivityLog::notifyAdmins(auth()->user()->email . ' restored the account of ' . $customer_support->email);

		return response()->json(['rsp' => true], 204);
	}

	public function deleteCustomerSupport(CustomerSupport $customer_support)
	{
		ActivityLog::notifyAdmins(auth()->user()->email . ' permanently deleted the account of ' . $customer_support->email);

		$customer_support->forceDelete();

		return response()->json(['rsp' => true], 204);
	}
}
