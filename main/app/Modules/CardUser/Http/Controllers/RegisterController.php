<?php

namespace App\Modules\CardUser\Http\Controllers;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Modules\CardUser\Models\OTP;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Modules\CardUser\Http\Requests\RegistrationValidation;


class RegisterController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

	use RegistersUsers;
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
	 * The routes for registration
	 *
	 * @return void
	 */
	static function routes()
	{
		Route::group(['prefix' => 'auth'], function () {
			Route::post('register', 'RegisterController@register');
		});
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function register(RegistrationValidation $request)
	{
		// return $request;
		DB::beginTransaction();

		event(new Registered($card_user = $this->create($request->all())));

		/** Create OTP */
		$otp = $card_user->createOTP();

		/** Send OTP code */

		/** Log the user in */
		$token = (string)auth('card_user')->login($card_user);

		// dd(get_class($token));
		DB::commit();

		return $this->respondWithToken($token);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return CardUser
	 */
	protected function create(array $data): CardUser
	{

		// $url = request()->file('user_passport')->store('public/id_cards');
		// Storage::setVisibility($url, 'public');

		/** Replace the public part of the url with storage to make it accessible on the frontend */
		// $url = str_replace_first('public', '/storage', $url);

		$card_user = CardUser::create([
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'phone' => $data['phone'],
			'bvn' => $data['bvn']
		]);

		Log::critical($card_user->email . ' registered an account on the site.');

		return $card_user;
	}

	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken(string $token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => auth('card_user')->factory()->getTTL() * 60,
			'user' => $user = auth('card_user')->user(),
			'is_card_activated' => !$user->has_unactivated_card(),
			'is_card_requested' => $user->debit_card_requests()->exists(),
		], 201);
	}
}
