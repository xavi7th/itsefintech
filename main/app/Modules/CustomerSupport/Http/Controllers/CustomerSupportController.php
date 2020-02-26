<?php

namespace App\Modules\CustomerSupport\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\CustomerSupport\Models\SupportTicket;

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

			CustomerSupport::customerSupportRoutes();

			SupportTicket::customerSupportRoutes();
		});
	}
}
