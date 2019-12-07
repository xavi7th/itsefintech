<?php

namespace App\Modules\CardUser\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\CardUser\Http\Requests\LoginValidation;


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
		Route::group(['prefix' => 'auth'], function () {
			Route::post('login', 'LoginController@login');
			Route::post('refresh', 'LoginController@refresh');
			Route::post('logout', 'LoginController@logout')->middleware('auth:api');
		});
	}

	public function login(LoginValidation $request)
	{

		$credentials = request(['email', 'password']);


		if (!$token = auth()->attempt($credentials)) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}


		return $this->respondWithToken($token);

		// $user =  $this->guard()->attempt(
		// 	$request->only('email', 'password'),
		// 	$request->filled('remember')
		// );

		// Log::critical($user->email . ' logged into his dashboard');


		// if (Auth::appuser()->is_verified()) {
		// 	return response()->json(['status' => true], 202);
		// } else {
		// 	Auth::logout();
		// 	return response()->json(['message' => 'Unverified user'], 416);
		// }
	}

	public function refresh(Request $request)
	{
		return $this->respondWithToken(auth()->refresh());
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

		// auth()->logout();

		return response()->json(['message' => 'Successfully logged out']);
	}


	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth()->factory()->getTTL() * 60
		]);
	}

	/**
	 * Get the guard to be used during authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\StatefulGuard
	 */
	protected function guard()
	{
		return Auth::guard();
	}
}
