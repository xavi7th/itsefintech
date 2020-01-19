<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Modules\Admin\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ApiRoute;
use App\Modules\Admin\Models\Merchant;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardAdmin\Models\CardAdmin;
use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\CardUser\Models\DebitCardType;
use App\Modules\NormalAdmin\Models\NormalAdmin;
use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\NormalAdmin\Models\StockRequest;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\AccountOfficer\Models\AccountOfficer;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\SalesRep\Transformers\SalesRepDebitCardRequestTransformer;
use App\Modules\Admin\Models\Voucher;
use App\Modules\Admin\Models\VoucherRequest;

class AdminController extends Controller
{
	/**
	 * The admin routes
	 * @return Response
	 */
	public static function routes()
	{

		Route::get('/user-instance', function () {
			function activeGuard()
			{
				foreach (array_keys(config('auth.guards')) as $guard) {
					if (auth()->guard($guard)->check()) return $guard;
				}
				return null;
			}
			return  collect(['type' => activeGuard()])->merge(auth(activeGuard())->user());
		})->middleware('web');

		Route::group(['middleware' => 'web', 'prefix' => Admin::DASHBOARD_ROUTE_PREFIX,  'namespace' => 'App\\Modules\Admin\Http\Controllers'], function () {
			LoginController::routes();

			Route::group(['prefix' => 'api'], function () {

				Route::post('test-route-permission', function () {
					$api_route = ApiRoute::where('name', request('route'))->first();
					if ($api_route) {
						return ['rsp'  => $api_route->admins()->where('user_id', auth()->id())->exists()];
					} else {
						return response()->json(['rsp' => false], 410);
					}
				})->middleware('auth:admin');

				Route::get('statistics', function () {
					// $sales_rep_debit_cards = DebitCard::where('sales_rep_id', auth()->id())->count();
					$sales_rep_sales = DebitCardRequest::where('sales_rep_id', auth()->id())->get();
					return [
						'total_assigned_cards' =>  DebitCard::where('sales_rep_id', auth()->id())->count(),
						'total_allocated_cards' => DebitCard::where('sales_rep_id', auth()->id())->where('card_user_id', '<>', null)->count(),
						'total_unallocated_cards' =>  DebitCard::where('sales_rep_id', auth()->id())->where('card_user_id', null)->count(),
						'total_sales_amount' => $sales_rep_sales->where('is_payment_confirmed', true)->count() * config('app.card_cost'),
						'total_cards_sold' => $sales_rep_sales->where('is_payment_confirmed', true)->count(),
						'sales_rep_sales' => (new SalesRepDebitCardRequestTransformer)->collectionTransformer($sales_rep_sales, 'transformForSalesRepViewSales')['debit_card_requests'],
						'recent_activities' => auth()->user()->activities()->take(4)->latest()->get(),
						'monthly_summary' => DebitCardRequest::whereMonth('created_at', now()->month())->where('sales_rep_id', auth()->id())->groupBy('day')
							->orderBy('day', 'DESC')->get([DB::raw('Date(created_at) as day'), DB::raw('COUNT(*) as "num_of_sales"')]),
						'unpaid_sales' => $sales_rep_sales->where('is_paid', false)->count(),
						'unconfirmed_sales' => $sales_rep_sales->where('is_payment_confirmed', false)->count(),
					];
				})->middleware('auth:admin');

				CardUser::adminRoutes();

				Admin::routes();

				NormalAdmin::routes();

				Accountant::routes();

				AccountOfficer::routes();

				CardAdmin::routes();

				CustomerSupport::routes();

				SalesRep::routes();

				DebitCard::adminRoutes();

				DebitCardType::adminRoutes();

				DebitCardRequest::adminRoutes();

				StockRequest::routes();

				LoanRequest::adminRoutes();

				LoanTransaction::adminRoutes();

				Merchant::adminRoutes();

				Voucher::adminRoutes();

				VoucherRequest::adminRoutes();
			});

			Route::group(['middleware' => ['auth:admin', 'admins']], function () {
				Route::get('/{subcat?}', function () {
					// return dd(ActivityLog::with('user')->get()); //with('user'));
					auth()->user()->api_routes()->syncWithoutDetaching(2);
					return view('admin::index');
				})->name('admin.dashboard')->where('subcat', '^((?!(api)).)*');
			});
		});
	}
}
