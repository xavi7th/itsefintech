<?php

namespace App\Modules\CardAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\CardAdmin\Models\CardAdmin;

class CardAdminController extends Controller
{
	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{
		Route::group(['middleware' => 'web', 'prefix' => CardAdmin::DASHBOARD_ROUTE_PREFIX], function () {
			LoginController::routes();

			Route::group(['middleware' => ['auth:card_admin', 'card_admins']], function () {

				Route::group(['prefix' => 'api'], function () {
					Route::post('test-route-permission', function () {
						$api_route = ApiRoute::where('name', request('route'))->first();
						if ($api_route) {
							return ['rsp'  => $api_route->card_admins()->where('user_id', auth('card_admin')->id())->exists()];
						} else {
							return response()->json(['rsp' => false], 410);
						}
					});
				});

				Route::get('/{subcat?}', function () {
					return view('cardadmin::index');
				})->name('cardadmin.dashboard')->where('subcat', '^((?!(api)).)*');
			});
		});
	}
}
