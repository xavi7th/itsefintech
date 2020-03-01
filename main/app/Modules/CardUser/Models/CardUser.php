<?php

namespace App\Modules\CardUser\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\CardUser\Models\OTP;
use App\Modules\Admin\Models\Voucher;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ActivityLog;
use Illuminate\Notifications\Notification;
use App\Modules\Admin\Models\VoucherRequest;
use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\Admin\Models\CardUserCategory;
use App\Modules\CardUser\Notifications\SendOTP;
use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\Admin\Models\MerchantTransaction;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\CardUser\Models\DebitCardTransaction;
use App\Modules\CardUser\Notifications\ProfileEdited;
use App\Modules\CardUser\Notifications\AccountCreated;
use App\Modules\CardUser\Models\DebitCardRequestStatus;
use App\Modules\Admin\Transformers\AdminUserTransformer;
use App\Modules\CardUser\Models\DebitCardFundingRequest;
use App\Modules\CardUser\Transformers\CardUserTransformer;
use App\Modules\Admin\Http\Requests\SetCardUserCreditLimitValidation;
use App\Modules\CardUser\Notifications\SendPasswordResetNotification;
use App\Modules\CardUser\Http\Requests\CardUserUpdateProfileValidation;

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
		'bvn',
		'school',
		'department',
		'level',
		'mat_no',
		'address',
		'city'
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

	protected static $editableProperties = [
		'first_name',
		'last_name',
		'password',
		'bvn',
		'school',
		'department',
		'level',
		'mat_no',
		'address',
		'city'
	];

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
	 * Create a new OTP for the user
	 *
	 * Deletes all previous OTP codes, creates a new unique one and then returns it
	 * @return int
	 **/
	public function createOTP()
	{
		$otp = unique_random('otps', 'code', null, 4);
		$this->otp()->create([
			'code' => $otp
		]);

		return $otp;
	}

	public function sendPasswordResetNotification($token)
	{
		$token = $this->generatePasswordResetToken();
		$this->notify(new SendPasswordResetNotification($token));
	}

	private function generatePasswordResetToken()
	{
		$token = unique_random('password_resets', 'token', null, 6);
		DB::table('password_resets')->updateOrInsert(
			['email' => $this->email],
			['token' => $token, 'created_at' => now()]

		);
		return $token;
	}

	public function vouchers()
	{
		return $this->hasMany(Voucher::class);
	}

	public function active_voucher()
	{
		return $this->hasOne(Voucher::class)->whereDate('created_at', '>', now()->subDays(config('app.voucher_validity_days'))->toDateString());
	}

	public function expired_vouchers()
	{
		return $this->hasMany(Voucher::class)->whereDate('created_at', '<', Carbon::today()->subDays(config('app.voucher_validity_days'))->toDateString());
	}

	public function voucher_request()
	{
		return $this->hasOne(VoucherRequest::class);
	}

	public function merchant_transactions()
	{
		return $this->hasMany(MerchantTransaction::class);
	}

	public function debit_merchant_transactions()
	{
		return $this->hasMany(MerchantTransaction::class)->where('trans_type', 'debit');
	}

	public function unapproved_merchant_transactions()
	{
		return $this->hasOne(MerchantTransaction::class)->where('trans_type', 'debit request');
	}

	public function repayment_merchant_transactions()
	{
		return $this->hasMany(MerchantTransaction::class)->where('trans_type', 'repayment');
	}

	public function due_for_merchant_loan()
	{
		return (boolean)$this->merchant_limit;
	}

	public function merchant_loan_balance()
	{
		return $this->vouchers()->sum('amount') - ($this->debit_merchant_transactions()->sum('amount') - $this->repayment_merchant_transactions()->sum('amount'));
	}

	public function pending_voucher_request()
	{
		return $this->voucher_request()->where('approved_at', null);
	}

	public function has_pending_voucher_request()
	{
		return $this->pending_voucher_request()->exists();
	}

	public function debit_cards()
	{
		return $this->hasMany(DebitCard::class);
	}

	public function first_debit_card()
	{
		return $this->hasOne(DebitCard::class)->oldest();
	}

	public function has_unactivated_card()
	{
		return $this->debit_cards()->exists() ? $this->debit_cards()->where('is_user_activated', false)->exists() : true;
	}

	public function debit_card_funding_request()
	{
		return $this->hasOne(DebitCardFundingRequest::class);
	}

	public function debit_card_transactions()
	{
		return $this->hasMany(DebitCardTransaction::class)->latest();
	}

	public function due_for_credit()
	{
		return (boolean)$this->credit_limit;
		return $this->debit_cards()->where('is_admin_activated', true)->where('is_suspended', false)->whereDate('activated_at', '<=', now()->subDays(30)->toDateString())->exists();
	}

	public function due_for_merchant_credit()
	{
		return (boolean)$this->merchant_limit;
	}

	public function activities()
	{
		return $this->morphMany(ActivityLog::class, 'user');
	}

	public function card_user_category()
	{
		return $this->belongsTo(CardUserCategory::class);
	}

	public function due_for_school_fees_loan()
	{
		return $this->card_user_category->is_student() && !is_null($this->school)  && !is_null($this->department)  && !is_null($this->level) && !is_null($this->mat_no);
	}

	public function debit_card_requests()
	{
		return $this->hasMany(DebitCardRequest::class);
	}

	public function pending_debit_card_requests()
	{
		return $this->hasOne(DebitCardRequest::class)->where('debit_card_request_status_id', '<>', DebitCardRequestStatus::delivered_id())->orWhere('debit_card_id', null);
	}

	public function last_debit_card_request()
	{
		return $this->hasOne(DebitCardRequest::class)->latest();
	}

	public function has_card_request()
	{
		return $this->hasOne(DebitCardRequest::class)->exists();
	}

	public function loan_transactions()
	{
		return $this->hasMany(LoanTransaction::class)->latest();
	}

	public function total_loan_amount()
	{
		return $this->loan_transactions()->where('transaction_type', 'loan')->sum('amount');
	}

	public function total_repayment_amount()
	{
		return $this->loan_transactions()->where('transaction_type', 'repayment')->sum('amount');
	}

	public function total_loan_balance()
	{
		return $this->total_loan_amount() - $this->total_repayment_amount();
	}

	public function running_loan_requests()
	{
		return $this->hasMany(LoanRequest::class)->where('paid_at', '<>', null);
	}


	public function loan_request()
	{
		return $this->hasMany(LoanRequest::class)->where('paid_at', null);
	}

	public function has_loan_request()
	{
		return $this->loan_request()->where('is_school_fees', false)->exists();
	}

	public function has_school_fees_request()
	{
		return $this->loan_request()->where('is_school_fees', true)->exists();
	}

	public function has_active_school_fees_loan()
	{
		return $this->loan_request()->where('is_school_fees', true)->where('approved_at', '<>', null)->exists();
	}

	public function activeDays(): int
	{
		return intval(optional(optional($this->first_debit_card)->activated_at)->diffInDays(now()));
	}

	public function getAssignedCreditLimitAttribute(): float
	{
		return $this->credit_limit ?? 0; //?? $this->card_user_category()->first(['credit_limit'])['credit_limit'];
	}

	public function getBvnAttribute($value): string
	{
		return $value ? 'ending in ' . substr(decrypt($value), -4) : 'Not provided';
	}

	public function getFullBvnAttribute(): string
	{
		return $this->attributes['bvn'] ? decrypt($this->attributes['bvn']) : 'Not provided';
	}
	public function getFullNameAttribute(): string
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	public function setBvnAttribute($value)
	{
		$this->attributes['bvn'] = encrypt($value);
	}

	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = bcrypt($value);
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('card-users', 'CardUser@getAllCardUsers')->middleware('auth:admin,normal_admin,account_officer');

			Route::post('card-user/create', 'CardUser@createCardUser')->middleware('auth:admin');

			Route::get('card-user/{card_user}/bvn', 'CardUser@getFullBvnNumber')->middleware('auth:admin');

			Route::put('card-user/{card_user}/credit-limit', 'CardUser@setUserCreditLimit')->middleware('auth:account_officer');

			Route::put('card-user/{card_user}/merchant-limit', 'CardUser@setUserMerchantLimit')->middleware('auth:account_officer');

			Route::get('card-user/{card_user}/permissions', 'CardUser@getPermittedRoutes')->middleware('auth:admin');

			Route::put('card-user/{card_user}/permissions', 'CardUser@setCardUserPermittedRoutes')->middleware('auth:admin');

			Route::put('card-user/{card_user}/suspend', 'CardUser@suspendCardUser')->middleware('auth:account_officer');

			Route::put('card-user/{id}/restore', 'CardUser@unsuspendCardUser')->middleware('auth:account_officer');

			Route::delete('card-user/{card_user}/delete', 'CardUser@deleteCardUserAccount')->middleware('auth:admin');
		});
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' =>  '\App\Modules\CardUser\Models'], function () {
			Route::get('card-users/profile-details', 'CardUser@getCardUserProfileDetails');
			/** Redundant Route use /user instead */
			// Route::put('card-user/profile', 'CardUser@editCardUserProfileDetails')->middleware('auth:card_user');
			Route::get('card-users/categories', 'CardUser@getCardUserCategories');
		});

		Route::group(['prefix' => 'auth', 'middleware' => ['auth:card_user', 'card_users'], 'namespace' =>  '\App\Modules\CardUser\Models'], function () {

			Route::group(['middleware' => ['unverified_card_users']], function () {
				Route::get('/user/request-otp', 'CardUser@requestOTP');
				Route::put('/user/verify-otp', 'CardUser@verifyOTP');
			});

			Route::group(['middleware' => ['verified_card_users']], function () {
				Route::get('/user', 'CardUser@user');
				Route::put('/user', 'CardUser@updateUserProfile')->middleware('auth:card_user');
			});
		});
	}


	/**
	 * ! Card User Route methods
	 */


	public function requestOTP(Request $request)
	{
		/** Delete Previous OTP **/
		$request->user()->otp()->delete();

		/** Create new OTP */
		$otp = $request->user()->createOTP();

		/** Send the OTP notification */
		$request->user()->notify(new SendOTP($otp));

		ActivityLog::logUserActivity($request->user()->email . ' successfully requested a new OTP.');

		return response()->json(['message' => 'OTP sent'], 201);
	}

	public function verifyOTP(Request $request)
	{
		if ($request->user()->otp->code !== intval($request->otp)) {
			return response()->json(['message' => 'Invalid OTP code'], 422);
		}
		DB::beginTransaction();
		/** Verify the user **/
		$request->user()->otp_verified_at = now();
		$request->user()->save();

		/** Delete the otp code */
		$request->user()->otp()->delete();

		/** Send welcome message */
		ActivityLog::logUserActivity($request->user()->email . ' OTP successfully verified.');

		/**
		 * ! Disabled per app owner's request
		 */
		// $request->user()->notify(new AccountCreated);

		DB::commit();

		return response()->json(['message' => 'Account verified'], 205);
	}


	public function user(Request $request)
	{
		return response()->json((new CardUserTransformer)->transform(auth('card_user')->user()));
	}

	public function updateUserProfile(CardUserUpdateProfileValidation $request)
	{
		auth('card_user')->user()->update($request->except(['email', 'phone']));

		auth()->user()->notify(new ProfileEdited);

		return response()->json(['updated' => true], 204);
	}



	public function getCardUserCategories()
	{
		return CardUserCategory::get(['category_name', 'id']);
	}

	public function getCardUserProfileDetails()
	{
		return self::$editableProperties;
	}

	public function editCardUserProfileDetails()
	{
		$keys = [];
		foreach (request()->except('token') as $key => $value) {
			auth()->user()->$key = $value;
		}
		auth()->user()->save();

		ActivityLog::logUserActivity(auth()->user()->email . ' edited his profile.');

		// auth()->user()->notify(new ProfileEdited);


		return response()->json([], 204);
	}

	/**
	 * ! Admin route methods
	 */

	public function getAllCardUsers()
	{
		return (new AdminUserTransformer)->collectionTransformer(CardUser::withTrashed()->get(), 'transformForAdminViewCardUsers');
	}

	public function createCardUser()
	{
		try {
			DB::beginTransaction();
			$admin = CardUser::create(Arr::collapse([
				request()->all(),
				[
					'password' => bcrypt('itsefintech@admin'),
				]
			]));

			ActivityLog::logAdminActivity('New Card User account created. Details: ' . $admin->email);

			DB::commit();
			return response()->json(['rsp' => $admin], 201);
		} catch (\Throwable $e) {
			if (app()->environment() == 'local') {
				return response()->json(['error' => $e->getMessage()], 500);
			}
			return response()->json(['rsp' => 'error occurred'], 500);
		}
	}

	public function getPermittedRoutes(CardUser $card_user)
	{
		$permitted_routes = $card_user->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
			return $item->id;
		});

		$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
			return ['id' => $item->id, 'description' => $item->description];
		});

		return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
	}

	public function setCardUserPermittedRoutes(CardUser $card_user)
	{
		$card_user->api_routes()->sync(request('permitted_routes'));

		ActivityLog::logAdminActivity('Card User permitted routes updated. Card user details: ' . $card_user->email);

		return response()->json(['rsp' => true], 204);
	}

	public function suspendCardUser(CardUser $card_user)
	{
		$card_user->delete();

		ActivityLog::logAdminActivity('Card User account suspended. Card user details: ' . $card_user->email);

		return response()->json(['rsp' => true], 204);
	}

	public function unsuspendCardUser($id)
	{
		$card_user = CardUser::withTrashed()->find($id);
		$card_user->restore();

		ActivityLog::logAdminActivity('Card User account restored. Card user details: ' . $card_user->email);

		return response()->json(['rsp' => true], 204);
	}

	public function deleteCardUserAccount(CardUser $card_user)
	{
		$card_user->forceDelete();

		ActivityLog::logAdminActivity('Card User account deleted permanently. Card user details: ' . $card_user->email);

		return response()->json(['rsp' => true], 204);
	}

	public function getFullBvnNumber(CardUser $card_user)
	{
		ActivityLog::logAdminActivity('Card User\'s full BVN details accessed. Card user details: ' . $card_user->email);

		return response()->json(['full_bvn' => $card_user->full_bvn], 200);
	}

	public function setUserCreditLimit(SetCardUserCreditLimitValidation $request, CardUser $card_user)
	{
		$card_user->credit_limit = $request->input('amount');
		$card_user->credit_percentage = $request->input('interest');
		$card_user->save();

		ActivityLog::logAdminActivity('Card User\'s credit limit updated. Card user details: ' . $card_user->email);

		return response()->json(['rsp' => true], 204);
	}

	public function setUserMerchantLimit(SetCardUserCreditLimitValidation $request, CardUser $card_user)
	{
		$card_user->merchant_limit = $request->input('amount');
		$card_user->merchant_percentage = $request->input('interest');
		$card_user->save();

		ActivityLog::logAdminActivity('Card User\'s merchant limit updated. Card user details: ' . $card_user->email);

		return response()->json(['rsp' => true], 204);
	}
}
