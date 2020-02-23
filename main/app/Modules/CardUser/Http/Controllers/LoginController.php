<?php

namespace App\Modules\CardUser\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Http\Requests\LoginValidation;
use App\Modules\CardUser\Transformers\CardUserTransformer;
use App\Modules\CardUser\Notifications\LoginAttempt;

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
			Route::post('refresh', 'LoginController@refresh')->middleware('auth:card_user');
			Route::post('logout', 'LoginController@logout')->middleware('auth:card_user');
		});
	}

	public function login(LoginValidation $request)
	{
		$credentials = request(['email', 'password']);

		if (!$token = auth()->guard('card_user')->attempt($credentials)) {
			return response()->json(['error' => true, 'rsp' => 'Invalid details supplied'], 401);
		}
		if (!auth('card_user')->user()->is_otp_verified()) {
			auth('card_user')->logout();
			return generate_422_error(['error' => 'otp not verified']);
		}

		try {
			auth('card_user')->user()->notify(new LoginAttempt);
		} catch (\Throwable $th) {
			Log::alert('Login notification not sent to ' . auth('card_user')->user()->email);
		}

		return $this->respondWithToken($token);
	}

	public function refresh(Request $request)
	{
		return $this->respondWithToken(auth('card_user')->refresh());
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
			'expires_in' => auth('card_user')->factory()->getTTL() * 60,
			'user' => (new CardUserTransformer)->transform($user = auth('card_user')->user()),
			'is_card_activated' => (boolean)!$user->has_unactivated_card(),
			'is_card_requested' => (boolean)$user->debit_card_requests()->exists(),
			'is_otp_verified' => (boolean)$user->is_otp_verified(),
			'success' => (boolean)true
		]);
	}

	/**
	 * Get the guard to be used during authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\StatefulGuard
	 */
	protected function guard()
	{
		return Auth::guard('card_user');
	}
}
