<?php

namespace App\Modules\SalesRep\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\SalesRep\Models\SalesRep;

class SalesRepController extends Controller
{
	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{
		Route::group(['middleware' => 'web', 'prefix' => SalesRep::DASHBOARD_ROUTE_PREFIX], function () {
			LoginController::routes();

			Route::group(['middleware' => ['auth:sales_rep', 'sales_reps']], function () {

				Route::group(['prefix' => 'api'], function () {
					Route::post('test-route-permission', function () {
						$api_route = ApiRoute::where('name', request('route'))->first();
						if ($api_route) {
							return ['rsp'  => $api_route->sales_reps()->where('user_id', auth('sales_rep')->id())->exists()];
						} else {
							return response()->json(['rsp' => false], 410);
						}
					});
				});

				Route::get('/{subcat?}', function () {
					return view('salesrep::index');
				})->name('salesrep.dashboard')->where('subcat', '^((?!(api)).)*');
			});
		});
	}
}
