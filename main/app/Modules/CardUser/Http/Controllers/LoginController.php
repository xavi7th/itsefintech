<?php

namespace App\Modules\CardUser\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	// protected $redirectTo = route('appuser.dashboard');
	protected function redirectTo()
	{
		if (request()->ajax()) {
			if (Auth::appuser()->is_verified()) {
				return response()->json(['status' => true], 202);
			} else {
				Auth::logout();
				return response()->json(['message' => 'Unverified user'], 416);
			}
		}
		return route(User::dashboardRoute());
	}

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
		Route::get('/logn', function () {
			return ['here'];
		})->middleware('guest');

		Route::post('login', 'LoginController@login');
		Route::post('logout', 'LoginController@logout')->name('appuser.logout');
	}

	/**
	 * Validate the user login request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return void
	 */
	protected function validateLogin(Request $request)
	{
		dd('tgfg');
		$this->validate($request, [
			$this->username() => 'required|string',
			'password' => 'required|string',
		]);
	}

	/**
	 * The user has been authenticated. We can redirect them to where we want or leave empty for the redirectto property to handle
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function authenticated(Request $request, $user)
	{
		if (!User::isAppUser()) {
			Auth::logout();
			session()->invalidate();
			return response()->json(['message' => 'Access Denied'], 403);
		} elseif (!$user->is_active) {
			Auth::logout();
			if ($request->ajax()) {
				return response()->json(['msg' => 'Your account has been suspended from trading activities. Kindly contact your account administrator for more information.'], 205);
			}
			return redirect()->route('login');
		} else {
			Log::critical($user->email . ' logged into his dashboard');
			if ($request->ajax()) {
				if (Auth::appuser()->is_verified()) {
					return response()->json(['status' => true], 202);
				} else {
					Auth::logout();
					return response()->json(['message' => 'Unverified user'], 416);
				}
			}
			return redirect()->route(User::dashboardRoute());
		}
	}

	/**
	 * Get the login username to be used by the controller.
	 *
	 * @return string
	 */
	public function username()
	{
		return 'email';
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
