<?php

namespace App\Modules\CardUser\Models;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Modules\CardUser\Models\OTP;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\Admin\Models\CardUserCategory;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\Admin\Transformers\AdminUserTransformer;

class CardUser extends User
{
	protected $fillable = [
		'card_user_category_id',
		'first_name',
		'last_name',
		'email',
		'password',
		'phone',
		'user_passport',
		'bvn'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token', 'credit_limit', 'deleted_at', 'created_at', 'updated_at'
	];

	protected $appends = ['assigned_credit_limit'];

	protected $casts = [
		'can_withdraw' => 'boolean',
		'is_active' => 'boolean',
		'card_user_category_id' => 'integer',
	];
	protected $table = "card_users";
	const DASHBOARD_ROUTE_PREFIX = "user";

	static function canAccess()
	{
		return request()->user() instanceof CardUser;
	}

	public function is_otp_verified()
	{
		return $this->otp_verified_at !== null;
	}

	public function otp()
	{
		return $this->hasOne(OTP::class);
	}

	/**
	 * Create a new OIP for the user
	 *
	 * Deletes all previous OTP codes, creates a new unique one and then returns it
	 * @return int
	 **/
	public function createOTP(): int
	{
		$otp = unique_random('otps', 'code');
		$this->otp()->create([
			'code' => $otp
		]);

		return $otp;
	}


	public function debit_cards()
	{
		return $this->hasMany(DebitCard::class);
	}

	public function has_unactivated_card()
	{
		return $this->debit_cards()->where('is_user_activated', false)->exists();
	}

	public function due_for_credit()
	{
		return $this->debit_cards()->where('is_admin_activated', true)->where('is_suspended', false)->whereDate('activated_at', '<=', now()->subDays(30)->toDateString())->exists();
	}

	public function activities()
	{
		return $this->morphMany(ActivityLog::class, 'user');
	}

	public function card_user_category()
	{
		return $this->belongsTo(CardUserCategory::class);
	}

	public function debit_card_requests()
	{
		return $this->hasMany(DebitCardRequest::class);
	}

	public function has_card_request()
	{
		return $this->hasOne(DebitCardRequest::class)->exists();
	}

	public function loan_request()
	{
		return $this->hasOne(LoanRequest::class)->where('is_approved', false);
	}
	public function has_loan_request()
	{
		return $this->loan_request()->exists();
	}




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

	public function getAssignedCreditLimitAttribute(): float
	{
		return $this->credit_limit ?? $this->card_user_category()->first(['credit_limit'])['credit_limit'];
	}

	public function getBvnAttribute($value): string
	{
		return 'ending in ' . substr(decrypt($value), -4);
	}

	public function setBvnAttribute($value)
	{
		$this->attributes['bvn'] = encrypt($value);
	}

	static function adminRoutes()
	{
		Route::get('card-users', function () {
			return (new AdminUserTransformer)->collectionTransformer(CardUser::withTrashed()->get(), 'transformForAdminViewCardUsers');
		})->middleware('auth:admin');

		Route::post('card-user/create', function () {
			// return request()->all();
			try {
				DB::beginTransaction();
				$admin = CardUser::create(Arr::collapse([
					request()->all(),
					[
						'password' => bcrypt('itsefintech@admin'),
					]
				]));

				DB::commit();
				return response()->json(['rsp' => $admin], 201);
			} catch (\Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::get('card-user/{card_user}/permissions', function (CardUser $card_user) {
			$permitted_routes = $card_user->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
				return $item->id;
			});

			$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
				return ['id' => $item->id, 'description' => $item->description];
			});

			return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
		})->middleware('auth:admin');

		Route::put('card-user/{card_user}/permissions', function (CardUser $card_user) {
			$card_user->api_routes()->sync(request('permitted_routes'));
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('card-user/{card_user}/suspend', function (CardUser $card_user) {
			if ($card_user->id === auth()->id()) {
				return response()->json(['rsp' => false], 403);
			}
			$card_user->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('card-user/{id}/restore', function ($id) {
			CardUser::withTrashed()->find($id)->restore();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::delete('card-user/{card_user}/delete', function (CardUser $card_user) {
			if ($card_user->id === auth()->id()) {
				return response()->json(['rsp' => false], 403);
			}
			$card_user->forceDelete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}

	static function cardUserRoutes()
	{

		Route::get('card-users/categories', function () {
			return CardUserCategory::get(['category_name', 'id']);
		});
	}
}
