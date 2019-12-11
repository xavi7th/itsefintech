<?php

namespace App\Modules\CustomerSupport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Modules\CustomerSupport\Models\CustomerSupport;

/**
 *
 *  Login Controller
 * --------------------------------------------------------------------------
 *  This controller handles authenticating admins for the application and
 *  redirecting them to the admin dashboard screen. The controller uses a trait
 *  to conveniently provide its functionality to your applications.
 *
 */
class LoginController extends Controller
{

	use AuthenticatesUsers;

	/**
	 * Where to redirect admins after login.
	 *
	 * @var string
	 */
	protected function redirectTo()
	{
		return route(CustomerSupport::dashboardRoute());
	}

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest:customer_support')->except('logout');
	}

	static function routes()
	{
		Route::get('login', 'LoginController@showLoginForm')->name('customersupport.login');
		Route::post('login', 'LoginController@login')->middleware('throttle:5,1')->middleware('verified_customer_supports');
		Route::post('first-time', 'LoginController@resetPassword')->middleware('throttle:5,1');
		Route::post('logout', 'LoginController@logout')->name('customersupport.logout');
	}

	/**
	 * Show the application's login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showLoginForm(Request $request)
	{
		return view('customersupport::auth');
	}

	/**
	 * Show the application's login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function resetPassword()
	{
		$card_admin = CustomerSupport::where('email', request('email'))->firstOrFail();
		if ($card_admin && !$card_admin->is_verified()) {
			DB::beginTransaction();

			$card_admin->password = bcrypt(request('pw'));
			$card_admin->verified_at = now();
			$card_admin->api_routes()->attach(1);
			$card_admin->save();

			DB::commit();

			$this->guard()->login($card_admin);

			return response()->json(['status' => true], 204);
		}
		return response()->json(['status'], 403);
	}

	/**
	 * Validate the user login request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return void
	 */
	protected function validateLogin(Request $request)
	{
		$this->validate($request, [
			$this->username() => 'required|email',
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
		if (CustomerSupport::canAccess()) {
			if (Auth::customerSupport()->is_verified()) {
				return response()->json(['status' => true], 202);
			} else {
				$this->guard()->logout();
				session()->invalidate();
				return response()->json(['message' => 'Unverified user'], 416);
			}
		} else {
			$this->guard()->logout();
			session()->invalidate();
			return response()->json(['message' => 'Access Denied'], 401);
		}
		return redirect()->route(CustomerSupport::dashboardRoute());
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
	 * Get the guard to be used during authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\StatefulGuard
	 */
	protected function guard()
	{
		return Auth::guard('customer_support');
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

		return redirect()->route('customersupport.login');
	}
}
