<?php

namespace App\Modules\Admin\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\Admin\Models\VoucherRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Models\MerchantTransaction;
use App\Modules\Admin\Transformers\AdminVoucherTransformer;
use App\Modules\Admin\Http\Requests\CreateVoucherValidation;
use App\Modules\CardUser\Transformers\CardUserVoucherTransformer;

class Voucher extends Model
{
	use SoftDeletes;

	protected $fillable = ['code', 'amount'];

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

	public function getIsExpiredAttribute(): bool
	{
		return $this->created_at->diffInDays(now()) > config('app.max_voucher_duration');
	}

	public function getAmountSpentAttribute(): float
	{
		return $this->merchant_transactions()->where('trans_type', 'debit')->sum('amount');
	}

	public function getAmountPaidAttribute(): float
	{
		return $this->merchant_transactions()->where('trans_type', 'repayment')->sum('amount');
	}

	/**
	 * ? The balance left to be repayed or the amount left on the voucher to spend
	 */
	public function getAmountLeftAttribute(): float
	{
		return $this->breakdownStatistics()->total_repayment_amount - $this->amount_spent;
	}

	public function getRepaymentBalanceAttribute(): float
	{
		return $this->breakdownStatistics()->total_repayment_amount - $this->merchant_transactions()->where('trans_type', 'repayment')->sum('amount');
	}

	public function breakdownStatistics(): object
	{
		return (object)[
			'amount' => (float)$this->amount,
			'voucher_balance' => $this->amount - $this->amount_spent,
			'amount_spent' => $this->amount_spent,
			'amount_paid' => $this->amount_paid,
			'interest_rate' => (float)$interest_rate = $this->card_user->merchant_percentage,
			'total_interest_amount' => (float)round($total_interest_amount = ($interest_rate / 100) * $this->amount, 2),
			'total_repayment_amount' => (float)$total_repayment_amount = round($total_interest_amount + $this->amount, 2),
			'current_repayment_amount' => (float)round((((($interest_rate / 100) * $this->amount_spent) + $this->amount_spent) - $this->amount_paid), 2),
			'is_expired' => (boolean)$this->is_expired,
			'amount_in_kobo' => (float)round((((($interest_rate / 100) * $this->amount_spent) + $this->amount_spent) - $this->amount_paid), 2) * 100
		];
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models', 'middleware' => ['verified_card_users']], function () {
			Route::get('vouchers', 'Voucher@getCardUserVouchers')->middleware('auth:card_user');
			Route::get('voucher/active', 'Voucher@getCardUserActiveVoucher')->middleware('auth:card_user');
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
	 * ! Card User routes
	 */
	public function getCardUserVouchers()
	{
		return (new CardUserVoucherTransformer)->collectionTransformer(auth()->user()->vouchers, 'transformForCardUserListVouchers');
	}

	public function getCardUserActiveVoucher()
	{
		$active_voucher = auth()->user()->active_voucher;
		if ($active_voucher) {
			return (new CardUserVoucherTransformer)->transformForCardUserListVouchers($active_voucher, 'transformForCardUserListVouchers');
		} else {
			return response()->json(['message' => 'No active voucher at the moment'], 404);
		}
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
			$voucher_code = unique_random('vouchers', 'code', null, 10);
			$voucher = Voucher::create(Arr::add($request->except('code'), 'code', $voucher_code));
		} else {
			$voucher = Voucher::create($request->all());
		}

		ActivityLog::logAdminActivity(auth()->user()->email . ' created a voucher for ' . to_naira($voucher->amount));

		return response()->json(['voucher' => $voucher], 201);
	}
}
