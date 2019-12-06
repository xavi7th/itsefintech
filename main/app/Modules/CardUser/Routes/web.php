<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('')->group(function () {
	Route::get('/', 'CardUserController@index');
});

Route::get('/site/setup/{key?}',  function ($key = null) {

	if ($key == config('app.migration_key')) {
		// dd(config('app.migration_key'));

		try {
			echo '<br>init storage:link...';
			$rsp = Artisan::call('storage:link');
			echo 'done storage:link. Result: ' . $rsp;

			echo '<br>init migrate:fresh...';
			$rsp =  Artisan::call('migrate:fresh');
			echo 'done migrate:fresh. Result: ' . $rsp;

			echo '<br>init module:seed...';
			$rsp =  Artisan::call('module:seed');
			echo 'done module:seed. Result: ' . $rsp;
		} catch (Exception $e) {
			Response::make($e->getMessage(), 500);
		}
	} else {
		App::abort(404);
	}
});
