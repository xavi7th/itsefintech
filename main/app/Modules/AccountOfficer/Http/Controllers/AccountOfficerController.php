<?php

namespace App\Modules\AccountOfficer\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\AccountOfficer\Models\AccountOfficer;
use App\Modules\AccountOfficer\Http\Controllers\LoginController;

class AccountOfficerController extends Controller
{
	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{
		Route::group(['middleware' => 'web', 'prefix' => AccountOfficer::DASHBOARD_ROUTE_PREFIX], function () {
			LoginController::routes();

			Route::group(['middleware' => ['auth:account_officer', 'account_officers']], function () {

				Route::group(['prefix' => 'api'], function () {
					Route::post('test-route-permission', function () {
						$api_route = ApiRoute::where('name', request('route'))->first();
						if ($api_route) {
							return ['rsp'  => $api_route->account_officers()->where('user_id', auth('account_officer')->id())->exists()];
						} else {
							return response()->json(['rsp' => false], 410);
						}
					});
				});

				Route::get('/{subcat?}', function () {
					return view('accountofficer::index');
				})->name('accountofficer.dashboard')->where('subcat', '^((?!(api)).)*');
			});
		});
	}
}
