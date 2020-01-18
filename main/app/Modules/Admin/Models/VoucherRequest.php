<?php

namespace App\Modules\Admin\Models;

use App\Modules\Admin\Models\Voucher;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Http\Requests\CreateVoucherRequestValidation;
use App\Modules\CardUser\Transformers\CardUserVoucherRequestTransformer;

class VoucherRequest extends Model
{
	use SoftDeletes;

	protected $fillable = ['code', 'amount'];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function voucher()
	{
		return $this->hasOne(Voucher::class);
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			Route::get('voucher-request', 'VoucherRequest@getVoucherRequest')->middleware('auth:card_user');
			Route::post('voucher-request/create', 'VoucherRequest@makeVoucherRequest')->middleware('auth:card_user');
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			Route::get('voucher-requests', 'VoucherRequest@getAllVoucherRequests')->middleware('auth:admin,normal_admin');
			Route::post('voucher-request/create', 'VoucherRequest@createVoucherRequest')->middleware('auth:admin,normal_admin');
		});
	}

	/**
	 * ! Card User routes
	 */
	public function makeVoucherRequest(CreateVoucherRequestValidation $request)
	{
		$voucher_request = auth()->user()->voucher_request()->create($request->all());
		return response()->json((new CardUserVoucherRequestTransformer)->transform($voucher_request), 201);
	}

	/**
	 * ! Admin routes
	 */
	public function getAllVoucherRequests()
	{
		return (new AdminVoucherRequestTransformer)->collectionTransformer(self::all(), 'transformForAdminViewVoucherRequests');
	}
	public function createVoucherRequest(CreateVoucherRequestValidation $request)
	{

		if ($request->auto_generate) {

			$voucher_request_code = unique_random('voucher_requests', 'code', null, 8);
			$voucher_request = VoucherRequest::create(Arr::add($request->except('code'), 'code', $voucher_request_code));
		} else {
			$voucher_request = VoucherRequest::create($request->all());
		}
		return response()->json(['voucher_request' => $voucher_request], 201);
	}
}
