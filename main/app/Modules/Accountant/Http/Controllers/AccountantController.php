<?php

namespace App\Modules\Accountant\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\VoucherRequest;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\CardUser\Models\DebitCardRequest;
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

			Accountant::accountantRoutes();

			DebitCardRequest::accountantRoutes();
		});
	}
}
