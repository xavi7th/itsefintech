<?php

namespace App\Modules\CardUser\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\CardUser\Models\DebitCardType;
use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\CardUser\Http\Controllers\LoginController;
use App\Modules\CardUser\Http\Controllers\RegisterController;
use App\Modules\Admin\Models\VoucherRequest;
use App\Modules\Admin\Models\Voucher;
use App\Modules\CardUser\Models\DebitCardFundingRequest;

class CardUserController extends Controller
{
	/**
	 * The card user routes
	 * @return Response
	 */
	public static function routes()
	{

		Route::group(['prefix' => 'v1'], function () {

			LoginController::routes();

			RegisterController::routes();

			Route::get('/', 'CardUserController@index');

			CardUser::cardUserRoutes();

			LoanTransaction::cardUserRoutes();

			LoanRequest::cardUserRoutes();

			DebitCardType::cardUserRoutes();

			Voucher::cardUserRoutes();

			VoucherRequest::cardUserRoutes();

			DebitCard::cardUserRoutes();

			DebitCardFundingRequest::cardUserRoutes();
		});
	}

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		return ['msg' => 'Welcome to ' . config('app.name') . ' API'];
	}
}
