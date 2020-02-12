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
use App\Modules\CardUser\Models\DebitCardTransaction;
use App\Modules\Admin\Models\Merchant;
use App\Modules\CardUser\Models\Notification;

class CardUserController extends Controller
{
	/**
	 * The card user routes
	 * @return Response
	 */
	public static function routes()
	{

		Route::group(['prefix' => 'v1'], function () {

			Route::get('/', 'CardUserController@index');

			LoginController::routes();

			RegisterController::routes();

			ForgotPasswordController::routes();

			ResetPasswordController::routes();

			CardUser::cardUserRoutes();

			LoanTransaction::cardUserRoutes();

			LoanRequest::cardUserRoutes();

			DebitCardType::cardUserRoutes();

			Voucher::cardUserRoutes();

			VoucherRequest::cardUserRoutes();

			DebitCard::cardUserRoutes();

			DebitCardTransaction::cardUserRoutes();

			DebitCardFundingRequest::cardUserRoutes();

			Merchant::cardUserRoutes();

			Notification::cardUserRoutes();
		});
	}


	public function index()
	{
		return view('index');
	}

	public function merchantPay()
	{
		return view('merchants');
	}

	public function cards()
	{
		return view('cards');
	}

	public function contactUs()
	{
		return response()->json('All fields required', 422);
	}
}
