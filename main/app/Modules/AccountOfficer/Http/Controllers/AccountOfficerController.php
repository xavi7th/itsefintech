<?php

namespace App\Modules\AccountOfficer\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
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

			AccountOfficer::accountOfficerRoutes();
		});
	}
}
