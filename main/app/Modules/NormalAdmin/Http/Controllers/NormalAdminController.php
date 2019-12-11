<?php

namespace App\Modules\NormalAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\NormalAdmin\Models\NormalAdmin;
use App\Modules\NormalAdmin\Http\Controllers\LoginController;

class NormalAdminController extends Controller
{

	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{
		Route::group(['middleware' => 'web', 'prefix' => NormalAdmin::DASHBOARD_ROUTE_PREFIX], function () {
			LoginController::routes();

			Route::group(['middleware' => ['auth:normal_admin', 'normal_admins']], function () {

				Route::group(['prefix' => 'api'], function () {
					Route::post('test-route-permission', function () {
						$api_route = ApiRoute::where('name', request('route'))->first();
						if ($api_route) {
							return ['rsp'  => $api_route->normal_admins()->where('user_id', auth()->id())->exists()];
						} else {
							return response()->json(['rsp' => false], 410);
						}
					});
				});

				Route::get('/{subcat?}', function () {
					// auth()->logout();
					// auth()->user()->api_routes()->sync([12, 2, 3, 4, 6]);
					return view('normaladmin::index');
				})->name('normaladmin.dashboard')->where('subcat', '^((?!(api)).)*');
			});
		});
	}
}
