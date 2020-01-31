<?php

namespace App\Modules\CardAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
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

				CardAdmin::cardAdminRoutes();
			});
		});
	}
}
