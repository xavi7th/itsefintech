<?php

namespace App\Modules\CardUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

	use ResetsPasswords;

	/**
	 * Where to redirect users after resetting their password.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

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
	 * Register the typical reset password routes for an application.
	 *
	 * @return void
	 */

	static function routes()
	{
		Route::namespace('\App\Http\Controllers\Auth')->group(function () {
			Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
			Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');
		});
	}
}
