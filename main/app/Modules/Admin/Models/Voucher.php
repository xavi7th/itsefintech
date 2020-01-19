<?php

namespace App\Modules\Admin\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\Admin\Models\VoucherRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Models\MerchantTransaction;
use App\Modules\Admin\Transformers\AdminVoucherTransformer;
use App\Modules\Admin\Http\Requests\CreateVoucherValidation;

class Voucher extends Model
{
	use SoftDeletes;

	protected $fillable = ['code', 'amount'];

	const ROUTE_PREFIX = 'voucher';


	static function exists(string $data): bool
	{
		return self::where('code', $data)->exists();
	}

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function voucher_request()
	{
		return $this->hasOne(VoucherRequest::class);
	}

	public function merchant_transactions()
	{
		return $this->hasMany(MerchantTransaction::class);
	}


	public function breakdownStatistics(): object
	{

		return (object)[
			'amount' => (float)$this->amount,
			'interest_rate' => (float)$interest_rate = $this->card_user->merchant_percentage,
			'total_duration' => (string)($total_duration = 6) . ' months',
			'minimum_repayment_amount' => (float)round($this->amount * ($interest_rate / 100), 2),
			'total_interest_amount' => (float)round($total_interest_amount = ($interest_rate / 100) * $this->amount * $total_duration, 2),
			'total_repayment_amount' => (float)round($total_repayment_amount = $total_interest_amount + $this->amount, 2),
			'scheduled_repayment_amount' => (float)number_format($total_repayment_amount / $total_duration, 2, '.', '')
		];
	}

	public function getIsExpiredAttribute()
	{
		return $this->created_at->diffInDays(now()) > config('app.max_voucher_duration');
	}

	public function getAmountSpentAttribute()
	{
		return $this->merchant_transactions()->where('trans_type', 'debit')->sum('amount');
	}

	public function getAmountLeftAttribute()
	{
		return $this->amount - $this->amount_spent;
	}


	public function getRepaymentBalanceAttribute(): float
	{
		return $this->breakdownStatistics()->total_repayment_amount - $this->merchant_transactions()->where('trans_type', 'repayment')->sum('amount');
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
