<?php

namespace App\Modules\CardUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

	use SendsPasswordResetEmails;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Register the typical forgot password routes for an application.
	 *
	 * @return void
	 */
	static function routes()
	{
		Route::namespace('\App\Http\Controllers\Auth')->group(function () {
			Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
			Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
		});
	}
}
