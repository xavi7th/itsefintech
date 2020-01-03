<?php

namespace App\Modules\CardUser\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use App\Modules\Admin\Models\CardUserCategory;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\CardUser\Http\Controllers\LoginController;
use App\Modules\CardUser\Transformers\CardUserTransformer;
use App\Modules\CardUser\Http\Controllers\RegisterController;
use App\Modules\CardUser\Http\Requests\CardRequestValidation;
use App\Modules\CardUser\Http\Requests\CardActivationValidation;
use App\Modules\CardUser\Http\Requests\CardUserUpdateProfileValidation;
use App\Modules\CardUser\Models\LoanRequest;

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

			CardUser::cardUserRoutes();

			Route::group(['prefix' => 'auth', 'middleware' => ['auth:card_user', 'card_users']], function () {

				Route::group(['middleware' => ['unverified_card_users']], function () {
					Route::get('/user/request-otp', 'CardUserController@requestOTP');

					Route::put('/user/verify-otp', 'CardUserController@verifyOTP');
				});

				// Route::group(['middleware' => ['verified_card_users']], function () {
				Route::get('/user', 'CardUserController@user');
				Route::put('/user', 'CardUserController@updateUserProfile');
				// });
			});

			LoanRequest::cardUserRoutes();

			Route::group(['prefix' => 'card', 'middleware' => ['auth:card_user', 'card_users']], function () {
				Route::get('/list', 'CardUserController@getDebitCards');
				Route::post('/new', 'CardUserController@requestDebitCard');
				Route::put('/activate', 'CardUserController@activateDebitCard');
				Route::get('/{card_request}/status', 'CardUserController@trackDebitCard');
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
		return response()->json((new CardUserTransformer)->transform(auth('card_user')->user()));
	}

	public function updateUserProfile(CardUserUpdateProfileValidation $request)
	{
		auth('card_user')->user()->update($request->except(['email', 'bvn']));
		return response()->json(['updated' => true], 204);
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

	public function getDebitCards()
	{
		return response()->json(['cards' => auth('card_user')->user()->debit_cards], 200);
	}

	public function requestDebitCard(CardRequestValidation $request)
	{
		$card_request = $request->user()->debit_card_requests()->updateOrCreate(
			[
				'payment_method' => request('payment_method'),
				'address' => request('address'),
			],
			Arr::collapse(
				[
					$request->all(),
					[
						'debit_card_request_status_id' => 1,
					]
				]
			)
		);

		return response()->json($card_request, 201);
	}

	public function activateDebitCard(CardActivationValidation $request)
	{

		$debit_card = DebitCard::find($request->card_id);
		/**
		 * Test csc
		 */
		if (Hash::check($request->csc, $debit_card->csc)) {
			$debit_card->is_user_activated = true;
			$debit_card->save();
			return response()->json(['message' => 'Card Activated'], 204);
		} else {
			return response()->json(['message' => 'Invalid CSC'], 403);
		}
	}
	public function trackDebitCard(DebitCardRequest $card_request)
	{
		return response()->json(['status' => $card_request->debit_card_request_status->name], 200);
	}
}
