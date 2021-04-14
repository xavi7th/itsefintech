<?php

namespace App\Modules\CardUser\Http\Controllers;

use Illuminate\Http\Response;
use Nexmo\Client\Exception\Request;
use App\Http\Controllers\Controller;
use App\Modules\Admin\Models\Voucher;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\Merchant;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\Admin\Models\VoucherRequest;
use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\CardUser\Models\Notification;
use App\Modules\CardUser\Models\DebitCardType;
use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\CardUser\Models\DebitCardTransaction;
use App\Modules\CardUser\Models\DebitCardFundingRequest;
use Illuminate\Support\Facades\Notification as LarNotif;
use App\Modules\CardUser\Http\Controllers\LoginController;
use App\Modules\CardUser\Http\Controllers\RegisterController;
use App\Modules\CardUser\Notifications\SendPasswordResetNotification;

class CardUserController extends Controller
{
	/**
	 * The card user routes
	 * @return Response
	 */
	public static function routes()
	{
    // Route::get('/test-mail', function () {
    //   LarNotif::route('mail', 'xavi7th@gmail.com')->notify(new SendPasswordResetNotification(435678));
    //   return 'Sent';
    // });

		Route::group(['prefix' => 'v1'], function () {

			Route::get('/', 'CardUserController@index')->name('app.home');
			Route::get('cards', 'CardUserController@cards')->name('app.cards');
			Route::get('savings', 'CardUserController@savings')->name('app.savings');
			Route::get('credit-policy', 'CardUserController@creditPolicy')->name('app.credit_policy');
			Route::get('work-with-us', 'CardUserController@career')->name('app.career');
			Route::get('our-terms-and-conditions', 'CardUserController@terms')->name('app.terms');

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


	public function cards()
	{
		return view('cards');
	}


	public function savings()
	{
		return view('savings');
	}


	public function creditPolicy()
	{
		return view('credit-policy');
	}


	public function career()
	{
		return view('career');
	}


	public function terms()
	{
		return view('terms');
	}

	public function contactUs()
	{
		return response()->json('All fields required', 422);
	}
}
