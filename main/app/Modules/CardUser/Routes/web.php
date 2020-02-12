<?php

use Illuminate\Support\Facades\Artisan;

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

/**
	Route::get('mail', function () {
		$invoice = App\Invoice::find(1);

		return (new App\Notifications\InvoicePaid($invoice))
			->toMail($invoice->user);
	});
 **/


Route::prefix('')->group(function () {
	Route::get('/', 'CardUserController@index');
	Route::get('/merchant-pay', 'CardUserController@merchantPay');
	Route::get('/cards', 'CardUserController@cards');
	Route::post('/contact-us', 'CardUserController@contactUs');
});
