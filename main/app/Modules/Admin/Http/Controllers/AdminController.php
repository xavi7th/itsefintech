<?php

namespace App\Modules\Admin\Http\Controllers;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Modules\Admin\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\CardAdmin\Models\CardAdmin;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\NormalAdmin\Models\NormalAdmin;
use App\Modules\DispatchAdmin\Models\DispatchAdmin;
use App\Modules\AccountOfficer\Models\AccountOfficer;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\Admin\Transformers\AdminUserTransformer;

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
					return (new AdminUserTransformer)->collectionTransformer(Admin::withTrashed()->get(), 'transformForAdminViewAdmins');
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
				});

				Route::put('admin/{admin}/permissions', function (Admin $admin) {
					$admin->api_routes()->sync(request('permitted_routes'));
					return response()->json(['rsp' => true], 204);
				});

				Route::put('admin/{admin}/suspend', function (Admin $admin) {
					if ($admin->id === auth()->id()) {
						return response()->json(['rsp' => false], 403);
					}
					$admin->delete();
					return response()->json(['rsp' => true], 204);
				});

				Route::put('admin/{id}/restore', function ($id) {
					Admin::withTrashed()->find($id)->restore();
					return response()->json(['rsp' => true], 204);
				});

				Route::delete('admin/{admin}/delete', function (Admin $admin) {
					if ($admin->id === auth()->id()) {
						return response()->json(['rsp' => false], 403);
					}
					$admin->forceDelete();
					return response()->json(['rsp' => true], 204);
				});


				Route::get('normal-admins', function () {
					return (new AdminUserTransformer)->collectionTransformer(NormalAdmin::withTrashed()->get(), 'transformForAdminViewNormalAdmins');
				});

				Route::post('normal-admin/create', function () {
					// return request()->all();
					try {
						DB::beginTransaction();
						$admin = NormalAdmin::create(Arr::collapse([
							request()->all(),
							[
								'password' => bcrypt('itsefintech@admin'),
							]
						]));

						DB::commit();
						return response()->json(['rsp' => $admin], 201);
					} catch (\Throwable $e) {
						if (app()->environment() == 'local') {
							return response()->json(['error' => $e->getMessage()], 500);
						}
						return response()->json(['rsp' => 'error occurred'], 500);
					}
				});

				Route::get('normal-admin/{admin}/permissions', function (NormalAdmin $admin) {
					$permitted_routes = $admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
						return $item->id;
					});

					$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
						return ['id' => $item->id, 'description' => $item->description];
					});

					return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
				});

				Route::put('normal-admin/{admin}/permissions', function (NormalAdmin $admin) {
					$admin->api_routes()->sync(request('permitted_routes'));
					return response()->json(['rsp' => true], 204);
				});

				Route::put('normal-admin/{admin}/suspend', function (NormalAdmin $admin) {
					if ($admin->id === auth()->id()) {
						return response()->json(['rsp' => false], 403);
					}
					$admin->delete();
					return response()->json(['rsp' => true], 204);
				});

				Route::put('normal-admin/{id}/restore', function ($id) {
					NormalAdmin::withTrashed()->find($id)->restore();
					return response()->json(['rsp' => true], 204);
				});

				Route::delete('normal-admin/{admin}/delete', function (NormalAdmin $admin) {
					if ($admin->id === auth()->id()) {
						return response()->json(['rsp' => false], 403);
					}
					$admin->forceDelete();
					return response()->json(['rsp' => true], 204);
				});

				Route::get('accountants', function () {
					return (new AdminUserTransformer)->collectionTransformer(Accountant::withTrashed()->get(), 'transformForAdminViewAccountants');
				});

				Route::post('accountant/create', function () {
					// return request()->all();
					try {
						DB::beginTransaction();
						$accountant = Accountant::create(Arr::collapse([
							request()->all(),
							[
								'password' => bcrypt('itsefintech@accountant'),
							]
						]));

						DB::commit();
						return response()->json(['rsp' => $accountant], 201);
					} catch (Throwable $e) {
						if (app()->environment() == 'local') {
							return response()->json(['error' => $e->getMessage()], 500);
						}
						return response()->json(['rsp' => 'error occurred'], 500);
					}
				});

				Route::get('accountant/{accountant}/permissions', function (Accountant $accountant) {
					$permitted_routes = $accountant->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
						return $item->id;
					});

					$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
						return ['id' => $item->id, 'description' => $item->description];
					});

					return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
				});

				Route::put('accountant/{accountant}/permissions', function (Accountant $accountant) {
					$accountant->api_routes()->sync(request('permitted_routes'));
					return response()->json(['rsp' => true], 204);
				});

				Route::put('accountant/{accountant}/suspend', function (Accountant $accountant) {
					$accountant->delete();
					return response()->json(['rsp' => true], 204);
				});

				Route::put('accountant/{id}/restore', function ($id) {
					Accountant::withTrashed()->find($id)->restore();
					return response()->json(['rsp' => true], 204);
				});

				Route::delete('accountant/{accountant}/delete', function (Accountant $accountant) {
					$accountant->forceDelete();
					return response()->json(['rsp' => true], 204);
				});

				Route::get('account-officers', function () {
					return (new AdminUserTransformer)->collectionTransformer(AccountOfficer::withTrashed()->get(), 'transformForAdminViewAccountOfficers');
				});

				Route::post('account-officer/create', function () {
					// return request()->all();
					try {
						DB::beginTransaction();
						$account_officer = AccountOfficer::create(Arr::collapse([
							request()->all(),
							[
								'password' => bcrypt('itsefintech@account_officer'),
							]
						]));

						DB::commit();
						return response()->json(['rsp' => $account_officer], 201);
					} catch (Throwable $e) {
						if (app()->environment() == 'local') {
							return response()->json(['error' => $e->getMessage()], 500);
						}
						return response()->json(['rsp' => 'error occurred'], 500);
					}
				});

				Route::get('account-officer/{account_officer}/permissions', function (AccountOfficer $account_officer) {
					$permitted_routes = $account_officer->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
						return $item->id;
					});

					$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
						return ['id' => $item->id, 'description' => $item->description];
					});

					return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
				});

				Route::put('account-officer/{account_officer}/permissions', function (AccountOfficer $account_officer) {
					$account_officer->api_routes()->sync(request('permitted_routes'));
					return response()->json(['rsp' => true], 204);
				});

				Route::put('account-officer/{account_officer}/suspend', function (AccountOfficer $account_officer) {
					$account_officer->delete();
					return response()->json(['rsp' => true], 204);
				});

				Route::put('account-officer/{id}/restore', function ($id) {
					AccountOfficer::withTrashed()->find($id)->restore();
					return response()->json(['rsp' => true], 204);
				});

				Route::delete('account-officer/{account_officer}/delete', function (AccountOfficer $account_officer) {
					$account_officer->forceDelete();
					return response()->json(['rsp' => true], 204);
				});

				Route::get('card-admins', function () {
					return (new AdminUserTransformer)->collectionTransformer(CardAdmin::withTrashed()->get(), 'transformForAdminViewCardAdmins');
				});

				Route::post('card-admin/create', function () {
					// return request()->all();
					try {
						DB::beginTransaction();
						$card_admin = CardAdmin::create(Arr::collapse([
							request()->all(),
							[
								'password' => bcrypt('itsefintech@card_admin'),
							]
						]));

						DB::commit();
						return response()->json(['rsp' => $card_admin], 201);
					} catch (Throwable $e) {
						if (app()->environment() == 'local') {
							return response()->json(['error' => $e->getMessage()], 500);
						}
						return response()->json(['rsp' => 'error occurred'], 500);
					}
				});

				Route::get('card-admin/{card_admin}/permissions', function (CardAdmin $card_admin) {
					$permitted_routes = $card_admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
						return $item->id;
					});

					$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
						return ['id' => $item->id, 'description' => $item->description];
					});

					return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
				});

				Route::put('card-admin/{card_admin}/permissions', function (CardAdmin $card_admin) {
					$card_admin->api_routes()->sync(request('permitted_routes'));
					return response()->json(['rsp' => true], 204);
				});

				Route::put('card-admin/{card_admin}/suspend', function (CardAdmin $card_admin) {
					$card_admin->delete();
					return response()->json(['rsp' => true], 204);
				});

				Route::put('card-admin/{id}/restore', function ($id) {
					CardAdmin::withTrashed()->find($id)->restore();
					return response()->json(['rsp' => true], 204);
				});

				Route::delete('card-admin/{card_admin}/delete', function (CardAdmin $card_admin) {
					$card_admin->forceDelete();
					return response()->json(['rsp' => true], 204);
				});

				Route::get('customer-supports', function () {
					return (new AdminUserTransformer)->collectionTransformer(CustomerSupport::withTrashed()->get(), 'transformForAdminViewCustomerSupports');
				});

				Route::post('customer-support/create', function () {
					// return request()->all();
					try {
						DB::beginTransaction();
						$customer_support = CustomerSupport::create(Arr::collapse([
							request()->all(),
							[
								'password' => bcrypt('itsefintech@customer_support'),
							]
						]));

						DB::commit();
						return response()->json(['rsp' => $customer_support], 201);
					} catch (Throwable $e) {
						if (app()->environment() == 'local') {
							return response()->json(['error' => $e->getMessage()], 500);
						}
						return response()->json(['rsp' => 'error occurred'], 500);
					}
				});

				Route::get('customer-support/{customer_support}/permissions', function (CustomerSupport $customer_support) {
					$permitted_routes = $customer_support->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
						return $item->id;
					});

					$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
						return ['id' => $item->id, 'description' => $item->description];
					});

					return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
				});

				Route::put('customer-support/{customer_support}/permissions', function (CustomerSupport $customer_support) {
					$customer_support->api_routes()->sync(request('permitted_routes'));
					return response()->json(['rsp' => true], 204);
				});

				Route::put('customer-support/{customer_support}/suspend', function (CustomerSupport $customer_support) {
					$customer_support->delete();
					return response()->json(['rsp' => true], 204);
				});

				Route::put('customer-support/{id}/restore', function ($id) {
					CustomerSupport::withTrashed()->find($id)->restore();
					return response()->json(['rsp' => true], 204);
				});

				Route::delete('customer-support/{customer_support}/delete', function (CustomerSupport $customer_support) {
					$customer_support->forceDelete();
					return response()->json(['rsp' => true], 204);
				});

				Route::get('dispatch-admins', function () {
					return (new AdminUserTransformer)->collectionTransformer(DispatchAdmin::withTrashed()->get(), 'transformForAdminViewDispatchAdmins');
				});

				Route::post('dispatch-admin/create', function () {
					// return request()->all();
					try {
						DB::beginTransaction();
						$dispatch_admin = DispatchAdmin::create(Arr::collapse([
							request()->all(),
							[
								'password' => bcrypt('itsefintech@dispatch_admin'),
							]
						]));

						DB::commit();
						return response()->json(['rsp' => $dispatch_admin], 201);
					} catch (Throwable $e) {
						if (app()->environment() == 'local') {
							return response()->json(['error' => $e->getMessage()], 500);
						}
						return response()->json(['rsp' => 'error occurred'], 500);
					}
				});

				Route::get('dispatch-admin/{dispatch_admin}/permissions', function (DispatchAdmin $dispatch_admin) {
					$permitted_routes = $dispatch_admin->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
						return $item->id;
					});

					$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
						return ['id' => $item->id, 'description' => $item->description];
					});

					return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
				});

				Route::put('dispatch-admin/{dispatch_admin}/permissions', function (DispatchAdmin $dispatch_admin) {
					$dispatch_admin->api_routes()->sync(request('permitted_routes'));
					return response()->json(['rsp' => true], 204);
				});

				Route::put('dispatch-admin/{dispatch_admin}/suspend', function (DispatchAdmin $dispatch_admin) {
					$dispatch_admin->delete();
					return response()->json(['rsp' => true], 204);
				});

				Route::put('dispatch-admin/{id}/restore', function ($id) {
					DispatchAdmin::withTrashed()->find($id)->restore();
					return response()->json(['rsp' => true], 204);
				});

				Route::delete('dispatch-admin/{dispatch_admin}/delete', function (DispatchAdmin $dispatch_admin) {
					$dispatch_admin->forceDelete();
					return response()->json(['rsp' => true], 204);
				});

				Route::get('sales-reps', function () {
					return (new AdminUserTransformer)->collectionTransformer(SalesRep::withTrashed()->get(), 'transformForAdminViewSalesReps');
				});

				Route::post('sales-rep/create', function () {
					// return request()->all();
					try {
						DB::beginTransaction();
						$sales_rep = SalesRep::create(Arr::collapse([
							request()->all(),
							[
								'password' => bcrypt('itsefintech@sales_rep'),
							]
						]));

						DB::commit();
						return response()->json(['rsp' => $sales_rep], 201);
					} catch (Throwable $e) {
						if (app()->environment() == 'local') {
							return response()->json(['error' => $e->getMessage()], 500);
						}
						return response()->json(['rsp' => 'error occurred'], 500);
					}
				});

				Route::get('sales-rep/{sales_rep}/permissions', function (SalesRep $sales_rep) {
					$permitted_routes = $sales_rep->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
						return $item->id;
					});

					$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
						return ['id' => $item->id, 'description' => $item->description];
					});

					return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
				});

				Route::put('sales-rep/{sales_rep}/permissions', function (SalesRep $sales_rep) {
					$sales_rep->api_routes()->sync(request('permitted_routes'));
					return response()->json(['rsp' => true], 204);
				});

				Route::put('sales-rep/{sales_rep}/suspend', function (SalesRep $sales_rep) {
					$sales_rep->delete();
					return response()->json(['rsp' => true], 204);
				});

				Route::put('sales-rep/{id}/restore', function ($id) {
					SalesRep::withTrashed()->find($id)->restore();
					return response()->json(['rsp' => true], 204);
				});

				Route::delete('sales-rep/{sales_rep}/delete', function (SalesRep $sales_rep) {
					$sales_rep->forceDelete();
					return response()->json(['rsp' => true], 204);
				});
			});

			Route::get('/{subcat?}', function () {
				auth()->user()->api_routes()->syncWithoutDetaching(3);
				return view('admin::index');
			})->name('admin.dashboard')->where('subcat', '^((?!(api)).)*');
		});
	}
}
