<?php

namespace App\Modules\CardUser\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Modules\CardUser\Models\OTP;
use Illuminate\Support\Facades\Http;
use App\Modules\Admin\Models\Voucher;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\Admin\Models\VoucherRequest;
use App\Modules\CardUser\Models\LoanRequest;
use App\Modules\Admin\Models\CardUserCategory;
use App\Modules\CardUser\Events\UserBVNUpdated;
use App\Modules\CardUser\Notifications\SendOTP;
use App\Modules\Admin\Events\UserCreditLimitSet;
use App\Modules\CardUser\Models\LoanTransaction;
use App\Modules\Admin\Models\MerchantTransaction;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\Admin\Events\UserMerchantLimitSet;
use App\Modules\CardUser\Events\UserProfileUpdated;
use App\Modules\CardUser\Models\DebitCardTransaction;
use App\Modules\CardUser\Notifications\AccountCreated;
use App\Modules\CardUser\Models\DebitCardRequestStatus;
use App\Modules\Admin\Transformers\AdminUserTransformer;
use App\Modules\CardUser\Models\DebitCardFundingRequest;
use App\Modules\CardUser\Transformers\CardUserTransformer;
use App\Modules\Admin\Http\Requests\SetCardUserCreditLimitValidation;
use App\Modules\CardUser\Notifications\SendPasswordResetNotification;
use App\Modules\CardUser\Http\Requests\CardUserUpdateProfileValidation;

/**
 * App\Modules\CardUser\Models\CardUser
 *
 * @property int $id
 * @property int|null $card_user_category_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $date_of_birth
 * @property string|null $otp_verified_at
 * @property string|null $password
 * @property string|null $phone
 * @property string $address
 * @property string $city
 * @property string|null $school
 * @property string|null $department
 * @property string|null $level
 * @property string|null $mat_no
 * @property string|null $user_passport
 * @property string $bvn
 * @property float|null $merchant_limit
 * @property float|null $merchant_percentage
 * @property float|null $credit_limit
 * @property float|null $credit_percentage
 * @property bool $is_active
 * @property string|null $remember_token
 * @property string|null $bleyt_customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Voucher|null $active_voucher
 * @property-read \Illuminate\Database\Eloquent\Collection|ActivityLog[] $activities
 * @property-read int|null $activities_count
 * @property-read CardUserCategory|null $card_user_category
 * @property-read DebitCardFundingRequest|null $debit_card_funding_request
 * @property-read \Illuminate\Database\Eloquent\Collection|DebitCardRequest[] $debit_card_requests
 * @property-read int|null $debit_card_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|DebitCardTransaction[] $debit_card_transactions
 * @property-read int|null $debit_card_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CardUser\Models\DebitCard[] $debit_cards
 * @property-read int|null $debit_cards_count
 * @property-read \Illuminate\Database\Eloquent\Collection|MerchantTransaction[] $debit_merchant_transactions
 * @property-read int|null $debit_merchant_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Voucher[] $expired_vouchers
 * @property-read int|null $expired_vouchers_count
 * @property-read \App\Modules\CardUser\Models\DebitCard|null $first_debit_card
 * @property-read float $assigned_credit_limit
 * @property-read string $full_bvn
 * @property-read string $full_name
 * @property-read DebitCardRequest|null $last_debit_card_request
 * @property-read \Illuminate\Database\Eloquent\Collection|LoanRequest[] $loan_request
 * @property-read int|null $loan_request_count
 * @property-read \Illuminate\Database\Eloquent\Collection|LoanTransaction[] $loan_transactions
 * @property-read int|null $loan_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|MerchantTransaction[] $merchant_transactions
 * @property-read int|null $merchant_transactions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read OTP|null $otp
 * @property-read DebitCardRequest|null $pending_debit_card_requests
 * @property-read \Illuminate\Database\Eloquent\Collection|MerchantTransaction[] $repayment_merchant_transactions
 * @property-read int|null $repayment_merchant_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|LoanRequest[] $running_loan_requests
 * @property-read int|null $running_loan_requests_count
 * @property-read int|null $vouchers_count
 * @property-read MerchantTransaction|null $unapproved_merchant_transactions
 * @property-read VoucherRequest|null $voucher_request
 * @property-read \Illuminate\Database\Eloquent\Collection|Voucher[] $vouchers
 * @property-read string|null $plain_bvn
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereBleytCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereBvn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereCardUserCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereCreditPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereMatNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereMerchantLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereMerchantPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereOtpVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser whereUserPassport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser withoutBleytAccount()
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser withBleytAccount()
 * @mixin \Eloquent
 */
class CardUser extends User
{
  protected $fillable = ['card_user_category_id', 'first_name', 'last_name', 'email', 'password', 'phone', 'user_passport', 'bvn', 'school', 'department', 'level', 'mat_no', 'address', 'city'];

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

  protected static $editableProperties = ['first_name', 'last_name', 'password', 'bvn', 'school', 'department', 'level', 'mat_no', 'address', 'city'];

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
    return (bool)$this->merchant_limit;
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

  public function active_debit_cards()
  {
    return $this->debit_cards()->where('is_suspended', false);
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
    return (bool)$this->credit_limit;
    return $this->debit_cards()->where('is_admin_activated', true)->where('is_suspended', false)->whereDate('activated_at', '<=', now()->subDays(30)->toDateString())->exists();
  }

  public function due_for_merchant_credit()
  {
    return (bool)$this->merchant_limit;
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
    return $this->hasOne(DebitCardRequest::class)->where(function ($query) {
      $query->where('debit_card_request_status_id', '<>', DebitCardRequestStatus::delivered_id())
        ->orWhere('debit_card_id', null);
    });
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
    return rescue(fn () => intval(($this->first_debit_card)->activated_at->diffInDays(now())), 0, false);
  }

  public function hasAddress(): bool
  {
    return !($this->address == 'not supplied' || empty($this->address));
  }

  public function createBleytWallet(DebitCard $debitCard, string $bvn): object
  {

    if (!is_null($this->bleyt_customer_id)) {
      return (object)[
        'status' => false,
        'message' => 'VThis user already has a bleyt wallet'
      ];
    }

    $endpoint = config('services.bleyt.create_wallet_endpoint');

    if (!$debitCard) {
      return (object)[
        'status' => false,
        'message' => 'Valid capital X card not provided'
      ];
    }

    $dataSupplied = [
      'firstName' => $this->first_name,
      'lastName' => $this->last_name,
      'email' => $this->email,
      'phoneNumber' => $this->phone,
      'dateOfBirth' => $this->date_of_birth ?? now()->subYears(20)->toDateString(),
      'bvn' => $bvn,
    ];

    $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint, $dataSupplied);
    $receivedDetails = $response->object();

    if ($response->ok()) {
      $this->bleyt_customer_id = $receivedDetails->customer->id;
      $debitCard->bleyt_wallet_id = $receivedDetails->wallet->id;
      $debitCard->save();
      $this->save();

      return (object)[
        'status' => true,
        'message' => 'Bleyt wallet created',
        'bleyt_wallet_id' => $receivedDetails->wallet->id
      ];
    }
    else{
      return (object)[
        'status' => false,
        'message' => $receivedDetails->message
      ];
    }

    BleytResponse::logToDB($endpoint, $dataSupplied, $response, $this);
  }

  public function linkCardToBleytWallet(DebitCard $debitCard, string $bvn)
  {
    $endpoint1 = config('services.bleyt.issue_card_endpoint');
    $endpoint2 = config('services.bleyt.activate_card_endpoint');

    //Check if this card is already linked to bleyt
    if ($debitCard->is_bleyt_activated) {
      return (object)[
        'status' => false,
        'message' => 'This debit card is already linked to a Bleyt wallet'
      ];
    }

    if (!$this->hasAddress() || !$bvn) {
      return (object)[
        'status' => false,
        'message' => 'The user has no address or no bvn supplied '
      ];
    }

    if(!$this->bleyt_customer_id) {
      return (object)[
        'status' => false,
        'message' => 'This user has no Bleyt Wallet to bind this card to'
      ];
    }

    $dataSupplied1 = [
      'customerId' => $this->bleyt_customer_id,
      'address1' => $this->address . ' ' . $this->city,
      'address2' => $this->address . ' ' . $this->city,
      'bvn' => $bvn
    ];

    $dataSupplied2 = [
      'customerId' => $this->bleyt_customer_id,
      'bvn' => $bvn,
      'last6' => $debitCard->last6_digits,
    ];

    $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint1, $dataSupplied1);
    BleytResponse::logToDB($endpoint1, $dataSupplied1, $response, $this);

    if ($response->ok()) {
      $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint2, $dataSupplied2);
      BleytResponse::logToDB($endpoint2, $dataSupplied2, $response, $this);

      $debitCard->is_bleyt_activated = true;
      $debitCard->save();

      return (object)[
        'status' => true,
        'message' => 'Card linked to bleyt wallet'
      ];
    }
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

  public function getPlainBvnAttribute(): ?string
  {
    return $this->attributes['bvn'] ? decrypt($this->attributes['bvn']) : null;
  }

  public function getFullNameAttribute(): string
  {
    return $this->first_name . ' ' . $this->last_name;
  }

  public function setBvnAttribute($value): void
  {
    $this->attributes['bvn'] = encrypt($value);
  }

  public function setPasswordAttribute($value): void
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

      Route::put('card-user/{card_user}/update-kyc', 'CardUser@updateCardUserAccount')->middleware('auth:admin');

      Route::delete('card-user/{card_user}/delete', 'CardUser@deleteCardUserAccount')->middleware('auth:admin');
    });
  }

  static function cardUserRoutes()
  {
    Route::group(['namespace' =>  '\App\Modules\CardUser\Models'], function () {
      Route::get('card-users/profile-details', 'CardUser@getCardUserProfileDetails');
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

    $request->user()->notify(new AccountCreated);

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

    if ($request->user()->isDirty('bvn')) {
      event(new UserBVNUpdated($request->user()));
    } else {
      event(new UserProfileUpdated($request->user()));
    }


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

  /**
   * ! Admin route methods
   */

  public function getAllCardUsers(Request $request)
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

  public function updateCardUserAccount(Request $request, self $cardUser)
  {
    $cardUser->email = $request->email ?? $cardUser->email;
    if ($request->phone) {
      $cardUser->phone = '+234' . substr($request->phone, -10);
    }
    $cardUser->save();

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

    event(new UserCreditLimitSet($card_user, floatval($request->input('amount')), floatval($request->input('interest'))));

    $card_user->credit_limit = $request->input('amount');
    $card_user->credit_percentage = $request->input('interest');
    $card_user->save();

    return response()->json(['rsp' => true], 204);
  }

  public function setUserMerchantLimit(SetCardUserCreditLimitValidation $request, CardUser $card_user)
  {
    event(new UserMerchantLimitSet($card_user, floatval($request->input('amount')), floatval($request->input('interest'))));

    $card_user->merchant_limit = $request->input('amount');
    $card_user->merchant_percentage = $request->input('interest');
    $card_user->save();

    return response()->json(['rsp' => true], 204);
  }

  /**
   * Scope a query to only include popular users.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeWithoutBleytAccount($query)
  {
    $query->whereBleytCustomerId(null);
  }

  public function scopeWithBleytAccount($query)
  {
    $query->whereNotNull('bleyt_customer_id');
  }
}
