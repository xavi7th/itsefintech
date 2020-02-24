<?php

namespace App\Modules\CardAdmin\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardAdmin\Models\CardAdmin;
use App\Modules\CardUser\Models\DebitCardRequest;

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

				DebitCardRequest::cardAdminRoutes();

				DebitCard::cardAdminRoutes();
			});
		});
	}
}
