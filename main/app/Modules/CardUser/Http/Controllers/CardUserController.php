<?php

namespace App\Modules\CardUser\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
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

		Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
			Route::group(['prefix' => 'auth'], function () {
				Route::get('/user', 'CardUserController@user');
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
		return $request->user();
	}
}
