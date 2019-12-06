<?php

namespace App\Modules\CardUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

	use ConfirmsPasswords;

	/**
	 * Where to redirect users when the intended url fails.
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
		$this->middleware('auth');
	}

	/**
	 * Register the typical confirm password routes for an application.
	 *
	 * @return void
	 */
	static function routes()
	{
		Route::namespace('\App\Http\Controllers\Auth')->group(function () {
			Route::get('password/confirm', 'ConfirmPasswordController@showConfirmForm')->name('password.confirm');
			Route::post('password/confirm', 'ConfirmPasswordController@confirm');
		});
	}
}
