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

Route::domain('pay.itsefintech.test')->group(function () {
	Route::get('/', 'CardUserController@merchantPay')->name('merchant.pay');
});

Route::prefix('')->group(function () {
	Route::get('/', 'CardUserController@index');
	Route::get('/cards', 'CardUserController@index');
	Route::get('/merchants', 'CardUserController@index');
	Route::get('/faqs', 'CardUserController@index');
	Route::get('/how-it-works', 'CardUserController@index');
	// Route::get('/merchant-login', 'CardUserController@merchantLogin')->name('merchants.login');
	// Route::post('/validate-merchant-code', 'CardUserController@validateMerchantCode');
	// Route::get('/process-merchant-transaction', 'CardUserController@processMerchantTransaction');
	// Route::post('/contact-us', 'CardUserController@contactUs');
});
