<?php

namespace App\Modules\Admin\Http\Controllers;

use App\ErrLog;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Modules\Admin\Models\Admin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\BasicSite\Models\AppUser;
use App\Modules\BasicSite\Models\Message;
use App\Modules\BasicSite\Models\Testimonial;
use App\Modules\Admin\Transformers\AdminUserTransformer;
use App\Modules\Transformers\AdminTestimonialTransformer;
use App\Modules\Admin\Transformers\AdminActivityTransformer;
use App\Modules\Admin\Transformers\AdminTransactionTransformer;

class AdminController extends Controller
{
	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{
		LoginController::routes();

		Route::group(['middleware' => ['auth:admin', 'admins']], function () {

			Route::group(['prefix' => 'api'], function () { });

			Route::get('/', function () {
				return ['auth' => true];
			})->name('admin.dashboard');
		});
	}
}
