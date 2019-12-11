<?php

namespace App\Modules\Accountant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\Accountant\Http\Controllers\LoginController;

class AccountantController extends Controller
{
	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{
		Route::group(['middleware' => 'web', 'prefix' => Accountant::DASHBOARD_ROUTE_PREFIX], function () {
			LoginController::routes();

			Route::group(['middleware' => ['auth:accountant', 'accountants']], function () {

				Route::group(['prefix' => 'api'], function () {
					Route::post('test-route-permission', function () {
						$api_route = ApiRoute::where('name', request('route'))->first();
						if ($api_route) {
							return ['rsp'  => $api_route->accountants()->where('user_id', auth('accountant')->id())->exists()];
						} else {
							return response()->json(['rsp' => false], 410);
						}
					});
				});

				Route::get('/{subcat?}', function () {
					// auth()->logout();
					// auth()->user()->api_routes()->sync([12, 2, 3, 4, 6]);
					return view('accountant::index');
				})->name('accountant.dashboard')->where('subcat', '^((?!(api)).)*');
			});
		});
	}
}
