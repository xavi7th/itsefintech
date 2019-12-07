<?php

namespace App\Modules\CardUser\Models;

use App\User;
use App\Modules\CardUser\Models\OTP;
use Illuminate\Database\Eloquent\Builder;

class CardUser extends User
{
	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'password',
		'phone',
		'user_passport',
		'bvn'
	];

	protected $casts = [
		'can_withdraw' => 'boolean',
		'is_active' => 'boolean'
	];
	protected $table = "card_users";
	const DASHBOARD_ROUTE_PREFIX = "user";

	static function canAccess()
	{
		return parent::isAppUser();
	}

	public function is_verified()
	{
		return $this->verified_at !== null;
	}

	public function otp()
	{
		return $this->hasOne(OTP::class);
	}
	public function transactions()
	{
		// return $this->hasMany(Transaction::class, 'user_id');
	}

	// public function withdrawal_requests()
	// {
	// 	return $this->hasMany(WithdrawalRequest::class);
	// }

	public function total_deposit_amount()
	{
		return $this->transactions()->where('trans_type', 'deposit')->sum('amount');
	}

	public function deposit_transactions()
	{
		return $this->transactions()->where('trans_type', 'deposit');
	}

	public function expected_withdrawal_amount()
	{
		return $this->transactions()->where('trans_type', 'deposit')->sum('target_amount');
	}

	public function total_withdrawal_amount()
	{
		return $this->transactions()->where('trans_type', 'withdrawal')->sum('amount');
	}

	public function total_withdrawalable_amount()
	{
		return $this->can_withdraw ? $this->expected_withdrawal_amount() : 0;
	}

	public function total_profit_amount()
	{
		return $this->transactions()->where('trans_type', 'profit')->sum('amount');
	}

	public function profit_transactions()
	{
		return $this->transactions()->where('trans_type', 'profit');
	}

	public function total_balance()
	{
		if ($this->total_profit_amount() <= 0) {
			return 0;
		}
		return $this->total_profit_amount() + $this->total_deposit_amount();
	}

	/**
	 * The booting method of the model
	 *
	 * @return void
	 */
	protected static function boot()
	{
		parent::boot();

		// static::addGlobalScope('appUsersOnly', function (Builder $builder) {
		// 	$builder->where('role_id', parent::$app_user_id);
		// });
	}
}
