<?php

namespace App\Modules\Admin\Http\Controllers;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Modules\Admin\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\CardAdmin\Models\CardAdmin;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\NormalAdmin\Models\NormalAdmin;
use App\Modules\DispatchAdmin\Models\DispatchAdmin;
use App\Modules\AccountOfficer\Models\AccountOfficer;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\Admin\Transformers\AdminUserTransformer;
use Tymon\JWTAuth\Claims\Custom;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\Admin\Models\ActivityLog;

class AdminController extends Controller
{
	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{

		Route::get('/user-instance', function () {
			function activeGuard()
			{
				foreach (array_keys(config('auth.guards')) as $guard) {
					if (auth()->guard($guard)->check()) return $guard;
				}
				return null;
			}
			return  collect(['type' => activeGuard()])->merge(auth(activeGuard())->user());
		})->middleware('web');

		Route::group(['middleware' => 'web', 'prefix' => Admin::DASHBOARD_ROUTE_PREFIX,  'namespace' => 'App\\Modules\Admin\Http\Controllers'], function () {
			LoginController::routes();

			Route::group(['prefix' => 'api'], function () {

				Route::post('test-route-permission', function () {
					$api_route = ApiRoute::where('name', request('route'))->first();
					if ($api_route) {
						return ['rsp'  => $api_route->admins()->where('user_id', auth()->id())->exists()];
					} else {
						return response()->json(['rsp' => false], 410);
					}
				})->middleware('auth:admin');

				CardUser::routes();

				Admin::routes();

				NormalAdmin::routes();

				Accountant::routes();

				AccountOfficer::routes();

				CardAdmin::routes();

				CustomerSupport::routes();

				SalesRep::routes();

				DebitCard::routes();

				DebitCardRequest::routes();
			});

			Route::group(['middleware' => ['auth:admin', 'admins']], function () {
				Route::get('/{subcat?}', function () {
					// return dd(ActivityLog::with('user')->get()); //with('user'));
					auth()->user()->api_routes()->syncWithoutDetaching(2);
					return view('admin::index');
				})->name('admin.dashboard')->where('subcat', '^((?!(api)).)*');
			});
		});
	}
}
