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
				NormalAdmin::normalAdminRoutes();
			});
		});
	}
}
