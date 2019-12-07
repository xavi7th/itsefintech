<?php

namespace App\Modules\CardUser\Http\Controllers;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GuzzleHttp\Exception\BadResponseException;

/**
 *
 *  Login Controller
 * --------------------------------------------------------------------------
 *  This controller handles authenticating users for the application and
 *  redirecting them to your home screen. The controller uses a trait
 *  to conveniently provide its functionality to your applications.
 *
 */
class LoginController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	static function routes()
	{
		Route::post('login', 'LoginController@login');
		Route::post('logout', 'LoginController@logout')->name('appuser.logout');
	}

	public function login(Request $request)
	{


		$this->validate($request, [
			'email' => 'required|string',
			'password' => 'required|string',
		]);

		$user =  $this->guard()->attempt(
			$request->only('email', 'password'),
			$request->filled('remember')
		);

		Log::critical($user->email . ' logged into his dashboard');


		if (Auth::appuser()->is_verified()) {
			return response()->json(['status' => true], 202);
		} else {
			Auth::logout();
			return response()->json(['message' => 'Unverified user'], 416);
		}
	}

	/**
	 * Log the user out of the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function logout(Request $request)
	{
		$this->guard()->logout();

		$request->session()->invalidate();

		return redirect()->route('home');
	}
}
