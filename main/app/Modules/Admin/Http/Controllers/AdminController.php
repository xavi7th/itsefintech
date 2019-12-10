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

			Route::group(['prefix' => 'api'], function () {
				Route::post('test-route-permission', function () {
					$api_route = ApiRoute::where('name', request('route'))->first();
					if ($api_route) {
						return ['rsp'  => $api_route->admins()->where('user_id', auth()->id())->exists()];
					} else {
						return response()->json(['rsp' => false], 410);
					}
				});

				Route::get('admins', function () {
					return (new AdminUserTransformer)->collectionTransformer(Admin::all(), 'transformForAdminViewAdmins');
				});

				Route::post('admin/create', function () {
					// return request()->all();
					try {
						DB::beginTransaction();
						$admin = Admin::create(Arr::collapse([
							request()->all(),
							[
								'password' => bcrypt('itsefintech@admin'),
							]
						]));
						//Give him access to dashboard
						// TODO set thin when admin fills his details and resets his password
						// $admin->permitted_api_routes()->attach(1);
						DB::commit();
						return response()->json(['rsp' => $admin], 201);
					} catch (\Throwable $e) {
						if (app()->environment() == 'local') {
							return response()->json(['error' => $e->getMessage()], 500);
						}
						return response()->json(['rsp' => 'error occurred'], 500);
					}
				});

				Route::get('admin/{admin}/permissions', function (Admin $admin) {
					$permitted_routes = $admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
						return $item->id;
					});

					$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
						return ['id' => $item->id, 'description' => $item->description];
					});

					return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
					return (new AdminUserTransformer)->collectionTransformer(Admin::all(), 'transformForAdminViewAdmins');
				});

				Route::put('admin/{admin}/permissions', function (Admin $admin) {
					$admin->api_routes()->sync(request('permitted_routes'));
					return response()->json(['rsp' => true], 204);
				});
			});

			Route::get('/{subcat?}', function () {
				return view('admin::index');
			})->name('admin.dashboard')->where('subcat', '^((?!(api)).)*');
		});
	}
}
