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
		event(new Registered($user = $this->create($request->all())));

		// $this->guard()->login($user);

		return $this->registered($request, $user);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{

		// $url = request()->file('user_passport')->store('public/id_cards');
		// Storage::setVisibility($url, 'public');

		/** Replace the public part of the url with storage to make it accessible on the frontend */
		// $url = str_replace_first('public', '/storage', $url);

		//Create an entry into the documents database
		DB::beginTransaction();
		$card_user = CardUser::create([
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'phone' => $data['phone'],
			'bvn' => $data['bvn']
		]);

		//Create OTP
		$otp = rand(1001, 999999);
		$card_user->otp()->create([
			'otp' => $otp
		]);

		//Send token

		return $card_user;
	}

	/**
	 * The user has been registered.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function registered(Request $request, $user)
	{
		Log::critical($user->email . ' registered an account on the site.');

		DB::commit();

		//Log the user in

		return response()->json(['status' => true], 201);
	}
}
