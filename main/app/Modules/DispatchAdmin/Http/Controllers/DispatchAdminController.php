<?php

namespace App\Modules\DispatchAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\DispatchAdmin\Models\DispatchAdmin;
use App\Modules\DispatchAdmin\Http\Controllers\LoginController;


class DispatchAdminController extends Controller
{
	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{
		Route::group(['middleware' => 'web', 'prefix' => DispatchAdmin::DASHBOARD_ROUTE_PREFIX], function () {
			LoginController::routes();

			Route::group(['middleware' => ['auth:dispatch_admin', 'dispatch_admins']], function () {

				Route::group(['prefix' => 'api'], function () {
					Route::post('test-route-permission', function () {
						$api_route = ApiRoute::where('name', request('route'))->first();
						if ($api_route) {
							return ['rsp'  => $api_route->dispatch_admins()->where('user_id', auth('dispatch_admin')->id())->exists()];
						} else {
							return response()->json(['rsp' => false], 410);
						}
					});
				});

				Route::get('/{subcat?}', function () {
					return view('dispatchadmin::index');
				})->name('dispatchadmin.dashboard')->where('subcat', '^((?!(api)).)*');
			});
		});
	}
}
