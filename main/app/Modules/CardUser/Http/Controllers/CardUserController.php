<?php

namespace App\Modules\CardUser\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Modules\CardUser\Http\Controllers\LoginController;
use App\Modules\CardUser\Http\Controllers\RegisterController;

class CardUserController extends Controller
{
	/**
	 * The card user routes
	 * @return Response
	 */
	public static function routes()
	{

		Route::group(['prefix' => 'v1'], function () {

			LoginController::routes();
			RegisterController::routes();

			Route::get('/', 'CardUserController@index');
		});

		Route::group(['prefix' => 'v1'], function () {



			Route::group(['prefix' => 'auth', 'middleware' => ['auth:card_user', 'card_users']], function () {

				Route::group(['middleware' => ['unverified_card_users']], function () {
					Route::get('/user/request-otp', 'CardUserController@requestOTP');

					Route::put('/user/verify-otp', 'CardUserController@verifyOTP');
				});

				Route::group(['middleware' => ['verified_card_users']], function () {
					Route::get('/user', 'CardUserController@user');
				});
			});
		});
	}

	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		return ['msg' => 'Welcome to ' . config('app.name') . ' API'];
	}

	public function user(Request $request)
	{
		return response()->json(auth()->user());
		// return $request->user();
	}

	public function requestOTP(Request $request)
	{
		/** Delete Previous OTP **/
		$request->user()->otp()->delete();

		$otp = $request->user()->createOTP();

		// Send otp
		return response()->json(['message' => 'OTP sent'], 201);
	}

	public function verifyOTP(Request $request)
	{
		if ($request->user()->otp->code !== intval($request->otp)) {
			return response()->json(['message' => 'Invalid OTP code'], 422);
		}
		/** Verify the user **/
		$request->user()->otp_verified_at = now();
		$request->user()->save();

		return response()->json(['message' => 'Account verified'], 205);
	}
}
