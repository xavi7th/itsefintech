<?php

namespace App\Modules\Accountant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Modules\Accountant\Models\Accountant;

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
		return route(Accountant::dashboardRoute());
	}

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest:accountant')->except('logout');
	}

	static function routes()
	{
		Route::get('login', 'LoginController@showLoginForm')->name('accountant.login');
		Route::post('login', 'LoginController@login')->middleware('throttle:5,1')->middleware('verified_accountants');
		Route::post('first-time', 'LoginController@resetPassword')->middleware('throttle:5,1');
		Route::post('logout', 'LoginController@logout')->name('accountant.logout');
	}

	/**
	 * Show the application's login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showLoginForm(Request $request)
	{
		return view('accountant::auth');
	}

	/**
	 * Show the application's login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function resetPassword()
	{
		$accountant = Accountant::where('email', request('email'))->firstOrFail();
		if ($accountant && !$accountant->is_verified()) {
			DB::beginTransaction();

			$accountant->password = bcrypt(request('pw'));
			$accountant->verified_at = now();
			$accountant->api_routes()->attach(1);
			$accountant->save();

			DB::commit();

			$this->guard()->login($accountant);

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
		if (Accountant::canAccess()) {
			if (Auth::accountant()->is_verified()) {
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
		return redirect()->route(Accountant::dashboardRoute());
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
		return Auth::guard('accountant');
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

		return redirect()->route('accountant.login');
	}
}
