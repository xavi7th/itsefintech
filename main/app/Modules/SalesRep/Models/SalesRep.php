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
use App\Modules\NormalAdmin\Models\StockRequest;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\Admin\Transformers\AdminUserTransformer;
use App\Modules\SalesRep\Transformers\SalesRepDebitCardRequestTransformer;

/**
 * App\Modules\SalesRep\Models\SalesRep
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CardUser\Models\DebitCard[] $assigned_debit_cards
 * @property-read int|null $assigned_debit_cards_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Modules\NormalAdmin\Models\StockRequest|null $stock_request
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\SalesRep\Models\SalesRep onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereBvn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereUserPassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\SalesRep\Models\SalesRep whereVerifiedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\SalesRep\Models\SalesRep withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\SalesRep\Models\SalesRep withoutTrashed()
 * @mixin \Eloquent
 */
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

	public function stock_request()
	{
		return $this->hasOne(StockRequest::class);
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

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\SalesRep\Models'], function () {
			Route::get('sales-reps', 'SalesRep@getAllSalesReps')->middleware('auth:admin');

			Route::post('sales-rep/create', 'SalesRep@createSalesRep')->middleware('auth:admin');

			Route::get('sales-rep/{sales_rep}/permissions', 'SalesRep@getSalesRepPermissions')->middleware('auth:admin');

			Route::put('sales-rep/{sales_rep}/permissions', 'SalesRep@editSalesRepPermissions')->middleware('auth:admin');

			Route::put('sales-rep/{sales_rep}/suspend', 'SalesRep@suspendSalesRep')->middleware('auth:admin');

			Route::put('sales-rep/{id}/restore', 'SalesRep@restoreSalesRep')->middleware('auth:admin');

			Route::delete('sales-rep/{sales_rep}/delete', 'SalesRep@deleteSalesRep')->middleware('auth:admin');
		});
	}

	static function salesRepRoutes()
	{
		Route::group(['middleware' => ['auth:sales_rep', 'sales_reps'], 'namespace' => '\App\Modules\SalesRep\Models'], function () {

			Route::group(['prefix' => 'api'], function () {
				Route::post('test-route-permission', 'SalesRep@testRoutePermission');

				Route::get('statistics', 'SalesRep@getDashboardStatistics')->middleware('auth:sales_rep');
			});

			Route::get('/{subcat?}', 'SalesRep@loadSalesRepApplication')->name('salesrep.dashboard')->where('subcat', '^((?!(api)).)*');
		});
	}

	public function loadSalesRepApplication()
	{
		return view('salesrep::index');
	}

	public function testRoutePermission()
	{
		$api_route = ApiRoute::where('name', request('route'))->first();
		if ($api_route) {
			return ['rsp'  => $api_route->sales_reps()->where('user_id', auth('sales_rep')->id())->exists()];
		} else {
			return response()->json(['rsp' => false], 410);
		}
	}


	public function getDashboardStatistics()
	{
		$sales_rep_sales = DebitCardRequest::where('sales_rep_id', auth()->id())->get();
		return [
			'total_assigned_cards' =>  DebitCard::where('sales_rep_id', auth()->id())->count(),
			'total_allocated_cards' => DebitCard::where('sales_rep_id', auth()->id())->where('card_user_id', '<>', null)->count(),
			'total_unallocated_cards' =>  DebitCard::where('sales_rep_id', auth()->id())->where('card_user_id', null)->count(),
			'total_sales_amount' => $sales_rep_sales->where('is_payment_confirmed', true)->count() * config('app.card_cost'),
			'total_cards_sold' => $sales_rep_sales->where('is_payment_confirmed', true)->count(),
			'sales_rep_sales' => (new SalesRepDebitCardRequestTransformer)->collectionTransformer($sales_rep_sales, 'transformForSalesRepViewSales')['debit_card_requests'],
			'monthly_summary' => DebitCardRequest::whereMonth('created_at', now()->month())->where('sales_rep_id', auth()->id())->groupBy('day')
				->orderBy('day', 'DESC')->get([DB::raw('Date(created_at) as day'), DB::raw('COUNT(*) as "num_of_sales"')]),
			'unpaid_sales' => $sales_rep_sales->where('is_paid', false)->count(),
			'unconfirmed_sales' => $sales_rep_sales->where('is_payment_confirmed', false)->count(),
		];
	}


	public function getAllSalesReps()
	{
		return (new AdminUserTransformer)->collectionTransformer(SalesRep::withTrashed()->get(), 'transformForAdminViewSalesReps');
	}

	public function createSalesRep()
	{
		try {
			DB::beginTransaction();
			$sales_rep = SalesRep::create(Arr::collapse([
				request()->all(),
				[
					'password' => bcrypt('itsefintech@sales_rep'),
				]
			]));

			DB::commit();

			ActivityLog::logAdminActivity(auth()->user()->email . ' created a sales rep account for ' . $sales_rep->email);

			return response()->json(['rsp' => $sales_rep], 201);
		} catch (Throwable $e) {
			if (app()->environment() == 'local') {
				return response()->json(['error' => $e->getMessage()], 500);
			}
			return response()->json(['rsp' => 'error occurred'], 500);
		}
	}

	public function getSalesRepPermissions(SalesRep $sales_rep)
	{
		$permitted_routes = $sales_rep->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
			return $item->id;
		});

		$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
			return ['id' => $item->id, 'description' => $item->description];
		});

		return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
	}

	public function editSalesRepPermissions(SalesRep $sales_rep)
	{
		$sales_rep->api_routes()->sync(request('permitted_routes'));

		ActivityLog::logAdminActivity(auth()->user()->email . ' edited the account permissions for ' . $sales_rep->email);

		return response()->json(['rsp' => true], 204);
	}

	public function suspendSalesRep(SalesRep $sales_rep)
	{
		ActivityLog::logAdminActivity(auth()->user()->email . ' suspended the account of ' . $sales_rep->email);

		$sales_rep->delete();

		return response()->json(['rsp' => true], 204);
	}

	public function restoreSalesRep($id)
	{
		$sales_rep = SalesRep::withTrashed()->find($id);

		$sales_rep->restore();

		ActivityLog::logAdminActivity(auth()->user()->email . ' restored the account of ' . $sales_rep->email);

		return response()->json(['rsp' => true], 204);
	}

	public function deleteSalesRep(SalesRep $sales_rep)
	{
		ActivityLog::logAdminActivity(auth()->user()->email . ' permanently deleted the account of ' . $sales_rep->email);

		$sales_rep->forceDelete();

		return response()->json(['rsp' => true], 204);
	}
}
