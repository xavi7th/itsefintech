<?php

namespace App\Modules\CardUser\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Modules\CardUser\Http\Controllers\RegistrationValidation;


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
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	// protected $redirectTo = route('appuser.dashboard');
	protected function redirectTo()
	{
		return route('appuser.dashboard');
	}

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
		Route::post('register', 'RegisterController@register');
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function register(RegistrationValidation $request)
	{
		return $request;
		event(new Registered($user = $this->create($request->all())));

		// $this->guard()->login($user);

		return $this->registered($request, $user)
			?: redirect($this->redirectPath());
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	// protected function validator(array $data)
	// {
	// 	return Validator::make($data, [
	// 		'name' => 'required|string|max:255',
	// 		'email' => 'required|string|email|max:255|unique:users',
	// 		'currency' => 'required|string',
	// 		'country' => 'required|string',
	// 		'phone' => 'required|string|unique:users,phone',
	// 		'password' => 'required|string|min:6|confirmed',
	// 		'agreement' => 'required|boolean|in:1,true',
	// 	]);
	// }

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{

		$url = request()->file('id_card')->store('public/id_cards');
		// Storage::setVisibility($url, 'public');

		/** Replace the public part of the url with storage to make it accessible on the frontend */
		$url = str_replace_first('public', '/storage', $url);

		//Create an entry into the documents database

		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => $data['password'],
			'unenc_password' => $data['password'],
			'currency' => $data['currency'],
			'country' => $data['country'],
			'phone' => $data['phone'],
			'id_card' => $url
		]);
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
		//
		Log::critical($user->email . ' registered an account on the site.');
		if ($request->ajax()) {
			return response()->json(['status' => true], 201);
		}
		return redirect('/login');
		dd('regh');
	}
}
