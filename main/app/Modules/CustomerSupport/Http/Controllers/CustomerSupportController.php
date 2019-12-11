<?php

namespace App\Modules\CustomerSupport\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;


class CustomerSupportController extends Controller
{
	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{
		Route::group(['middleware' => 'web', 'prefix' => CustomerSupport::DASHBOARD_ROUTE_PREFIX], function () {
			LoginController::routes();

			Route::group(['middleware' => ['auth:customer_support', 'customer_supports']], function () {

				Route::group(['prefix' => 'api'], function () {
					Route::post('test-route-permission', function () {
						$api_route = ApiRoute::where('name', request('route'))->first();
						if ($api_route) {
							return ['rsp'  => $api_route->customer_supports()->where('user_id', auth('customer_support')->id())->exists()];
						} else {
							return response()->json(['rsp' => false], 410);
						}
					});
				});

				Route::get('/{subcat?}', function () {
					return view('customersupport::index');
				})->name('customersupport.dashboard')->where('subcat', '^((?!(api)).)*');
			});
		});
	}
}
