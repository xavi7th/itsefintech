<?php

namespace App\Modules\Admin\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Models\MerchantTransaction;
use App\Modules\Admin\Transformers\AdminVoucherTransformer;
use App\Modules\Admin\Http\Requests\CreateVoucherValidation;

class Voucher extends Model
{
	use SoftDeletes;

	protected $fillable = ['code', 'amount'];

	const ROUTE_PREFIX = 'voucher';

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function merchant_transactions()
	{
		return $this->hasMany(MerchantTransaction::class);
	}

	public function getIsExpiredAttribute()
	{
		return $this->created_at->diffInDays(now()) > config('app.max_voucher_duration');
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			Route::get('vouchers', 'Voucher@getAllVouchers')->middleware('auth:admin,normal_admin');
			Route::post('voucher/repayment', 'Voucher@repayVoucher')->middleware('auth:admin,normal_admin');
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			Route::get('vouchers', 'Voucher@getAllVouchers')->middleware('auth:admin,normal_admin');
			Route::post('voucher/create', 'Voucher@createVoucher')->middleware('auth:admin,normal_admin');
		});
	}

	/**
	 * ! Admin routes
	 */
	public function getAllVouchers()
	{
		return (new AdminVoucherTransformer)->collectionTransformer(self::all(), 'transformForAdminViewVouchers');
	}
	public function createVoucher(CreateVoucherValidation $request)
	{

		if ($request->auto_generate) {

			$voucher_code = unique_random('vouchers', 'code', null, 8);
			$voucher = Voucher::create(Arr::add($request->except('code'), 'code', $voucher_code));
		} else {
			$voucher = Voucher::create($request->all());
		}
		return response()->json(['voucher' => $voucher], 201);
	}
}
