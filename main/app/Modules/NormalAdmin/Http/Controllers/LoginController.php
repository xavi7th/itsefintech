<?php

namespace App\Modules\NormalAdmin\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\NormalAdmin\Models\NormalAdmin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
		return route(NormalAdmin::dashboardRoute());
	}

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest:normal_admin')->except('logout');
	}

	static function routes()
	{
		Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
		Route::post('login', 'LoginController@login')->middleware('throttle:5,1')->middleware('verified_normal_admins');
		Route::post('first-time', 'LoginController@resetPassword')->middleware('throttle:5,1');
		Route::post('logout', 'LoginController@logout')->name('admin.logout');
	}

	/**
	 * Show the application's login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showLoginForm(Request $request)
	{
		return view('normaladmin::auth');
	}

	/**
	 * Show the application's login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function resetPassword()
	{
		$normal_admin = NormalAdmin::where('email', request('email'))->firstOrFail();
		if ($normal_admin && !$normal_admin->is_verified()) {
			DB::beginTransaction();

			$normal_admin->password = bcrypt(request('pw'));
			$normal_admin->verified_at = now();
			$normal_admin->api_routes()->attach(1);
			$normal_admin->save();

			DB::commit();

			$this->guard()->login($normal_admin);

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
		if (NormalAdmin::canAccess()) {
			if (Auth::normalAdmin()->is_verified()) {
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
		return redirect()->route(NormalAdmin::dashboardRoute());
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
		return Auth::guard('normal_admin');
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

		return redirect()->route('normaladmin.login');
	}
}
