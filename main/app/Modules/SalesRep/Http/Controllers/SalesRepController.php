<?php

namespace App\Modules\SalesRep\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\CardUser\Models\DebitCard;

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

			SalesRep::salesRepRoutes();

			DebitCard::salesRepRoutes();
		});
	}
}
