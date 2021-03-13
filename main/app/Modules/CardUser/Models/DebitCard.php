<?php

namespace App\Modules\CardUser\Models;

use Cache;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Modules\Admin\Models\ErrLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Models\BleytResponse;
use App\Modules\CardUser\Models\DebitCardType;
use Illuminate\Validation\ValidationException;
use App\Modules\CardUser\Emails\CardBlockRequest;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\Accountant\Events\DebitCardActivated;
use App\Modules\Accountant\Events\DebitCardRequested;
use App\Modules\CardUser\Models\DebitCardTransaction;
use App\Modules\CardUser\Models\DebitCardRequestStatus;
use App\Modules\CardUser\Models\DebitCardFundingRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Admin\Transformers\AdminDebitCardTransformer;
use App\Modules\CardUser\Http\Requests\CardFundingValidation;
use App\Modules\CardUser\Http\Requests\CardRequestValidation;
use App\Modules\Admin\Http\Requests\DebitCardCreationValidation;
use App\Modules\CardUser\Http\Requests\CardActivationValidation;
use App\Modules\CardUser\Http\Requests\CardDeactivationValidation;
use App\Modules\CardUser\Transformers\CardUserDebitCardTransformer;

/**
 * App\Modules\CardUser\Models\DebitCard
 *
 * @property int $id
 * @property int|null $sales_rep_id
 * @property int|null $card_user_id
 * @property int $debit_card_type_id
 * @property string $card_number
 * @property string $card_hash
 * @property string $csc
 * @property int $month
 * @property int $year
 * @property string|null $bleyt_wallet_id
 * @property int $is_bleyt_activated
 * @property int $is_user_activated
 * @property int $is_admin_activated
 * @property \Illuminate\Support\Carbon|null $activated_at
 * @property int $is_suspended
 * @property int|null $assigned_by
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read CardUser|null $card_user
 * @property-read \Illuminate\Database\Eloquent\Collection|DebitCardFundingRequest[] $debit_card_funding_request
 * @property-read int|null $debit_card_funding_request_count
 * @property-read DebitCardRequest|null $debit_card_request
 * @property-read \Illuminate\Database\Eloquent\Collection|DebitCardTransaction[] $debit_card_transactions
 * @property-read int|null $debit_card_transactions_count
 * @property-read DebitCardType $debit_card_type
 * @property-read mixed $exp_date
 * @property-read string $full_pan_number
 * @property-read mixed $last6_digits
 * @property-read DebitCardFundingRequest|null $new_debit_card_funding_request
 * @property-read SalesRep|null $sales_rep
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard bleytUnactivated()
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard newQuery()
 * @method static \Illuminate\Database\Query\Builder|DebitCard onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard pendingAdminActivation()
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereActivatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereAssignedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereBleytWalletId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereCardHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereCardUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereCsc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereDebitCardTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereIsAdminActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereIsBleytActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereIsSuspended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereIsUserActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereSalesRepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DebitCard whereYear($value)
 * @method static \Illuminate\Database\Query\Builder|DebitCard withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DebitCard withoutTrashed()
 * @mixin \Eloquent
 */
class DebitCard extends Model
{
  use SoftDeletes, Rememberable;

  protected $fillable = [
    'card_number', 'month', 'year', 'csc', 'sales_rep_id', 'card_user_id', 'debit_card_type_id'
  ];
  protected $dates = ['activated_at'];
  protected $rememberFor = 10;
  protected $rememberCachePrefix = 'debit-cards';

  protected $appends = ['exp_date'];

  protected $casts = [
    '$debitCard->is_bleyt_activated' => 'bool'
  ];

  protected static function getHashingAlgorithm()
  {
    return 'sha512';
  }

  static function hash(string $data): string
  {
    return hash(static::getHashingAlgorithm(), $data);
  }

  static function exists(string $data): bool
  {
    return self::where('card_hash', static::hash($data))->exists();
  }

  static function retrieve(string $data): DebitCard
  {
    return self::where('card_hash', static::hash($data))->first();
  }

  public function card_user()
  {
    return $this->belongsTo(CardUser::class);
  }

  public function sales_rep()
  {
    return $this->belongsTo(SalesRep::class);
  }

  public function debit_card_request()
  {
    return $this->hasOne(DebitCardRequest::class);
  }

  public function new_debit_card_funding_request()
  {
    return $this->hasOne(DebitCardFundingRequest::class)->where('is_funded', false);
  }

  public function debit_card_funding_request()
  {
    return $this->hasMany(DebitCardFundingRequest::class);
  }

  public function debit_card_transactions()
  {
    return $this->hasMany(DebitCardTransaction::class)->latest();
  }

  public function debit_card_type()
  {
    return $this->belongsTo(DebitCardType::class);
  }

  public function belongs_to_user(CardUser $card_user): bool
  {
    return $card_user->is($this->card_user);
  }

  public function getExpDateAttribute()
  {
    return Carbon::createFromDate($this->year, $this->month + 1, 1);
  }

  public function getFullPanNumberAttribute(): string
  {
    return decrypt($this->attributes['card_number']);
  }

  public function getCardNumberAttribute($value)
  {
    // return decrypt($value);
    return substr(decrypt($value), -4);
  }

  public function getLast6DigitsAttribute($value)
  {
    // return decrypt($value);
    return substr(decrypt($this->attributes['card_number']), -6);
  }

  public function setCardNumberAttribute($value)
  {
    $this->attributes['card_number'] = encrypt($value);
    $this->attributes['card_hash'] = static::hash($value);
  }

  public function setCscAttribute($value)
  {
    // $this->attributes['csc'] = bcrypt($value);
    $this->attributes['csc'] = $value;
  }

  /**
   * Scope a query to only include active users.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopePendingAdminActivation($query)
  {
    return $query->where('is_user_activated', true)->where('is_admin_activated', false);
  }

  static function cardUserRoutes()
  {

    Route::group(['prefix' => 'card', 'middleware' => ['auth:card_user', 'card_users', 'verified_card_users']], function () {
      Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
        Route::get('/list', 'DebitCard@getCardUserDebitCards');
        Route::post('/new', 'DebitCard@requestDebitCard');
        Route::put('/activate', 'DebitCard@activateCardUserDebitCard');
        Route::delete('deactivate', 'DebitCard@deactivateCardUserDebitCard');
        Route::get('/status', 'DebitCard@trackCardUserDebitCard');
        Route::get('/{debit_card}', 'DebitCard@getCardUserCardDetails');
        Route::get('/{debit_card}/balance', 'DebitCard@getCardUserCardBalance')->name('appuser.card.balance');
        Route::post('/{debit_card}/fund', 'DebitCard@fundCardUserCard');
      });
    });
  }

  static function salesRepRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models', 'prefix' => 'api'], function () {
      Route::put('debit-card/{debit_card}/allocate', 'DebitCard@allocateDebitCard')->middleware('auth:sales_rep');
    });
  }

  static function cardAdminRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models', 'prefix' => 'api'], function () {
      Route::put('debit-card/{debit_card}/activate', 'DebitCard@activateDebitCard')->middleware('auth:card_admin');
    });
  }

  static function normalAdminRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models', 'prefix' => 'api'], function () {
      Route::post('debit-card/create', 'DebitCard@createDebitCard')->middleware('auth:normal_admin');
      Route::put('debit-card/{debit_card}/assign', 'DebitCard@assignDebitCard')->middleware('auth:normal_admin');
    });
  }

  static function adminRoutes()
  {
    Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {

      Route::get('debit-cards', 'DebitCard@getDebitCards')->middleware('auth:admin,sales_rep,card_admin,normal_admin');

      Route::put('debit-card/{debit_card}/suspension', 'DebitCard@toggleDebitCardSuspendStatus')->middleware('auth:admin');

      Route::delete('debit-card/{debit_card}/delete', 'DebitCard@deleteDebitCard')->middleware('auth:admin');

      Route::get('debit-card/{debit_card}/pan', 'DebitCard@showFullPANNumber')->middleware('auth:admin');
    });
  }

  /**
   * ! Card User route methods
   */
  public function getCardUserDebitCards()
  {
    return response()->json((new CardUserDebitCardTransformer)->collectionTransformer(auth('card_user')->user()->active_debit_cards, 'transformForCardList'), 200);
  }

  public function getCardUserCardDetails(DebitCard $debit_card)
  {
    return response()->json((new CardUserDebitCardTransformer)->transformForCardDetails($debit_card), 200);
  }

  public function getCardUserCardBalance(Request $request, DebitCard $debitCard)
  {
    $endpoint = config('services.bleyt.check_card_balance_endpoint');

    $dataSupplied = [
        'customerId' => $request->user()->bleyt_customer_id
      ];
      $response = Http::withToken(config('services.bleyt.secret_key'))->get($endpoint, $dataSupplied);

      BleytResponse::logToDB($endpoint, $dataSupplied, $response, $request->user());

      if ($response->ok()) {
        $receivedDetails = $response->object();
        return response()->json((new CardUserDebitCardTransformer)->transformForCardBalance($receivedDetails), 200);
    } else {
      return response()->json($response->object(), $response->status());
    }
  }

  public function fundCardUserCard(CardFundingValidation $request, DebitCard $debitCard)
  {
    $endpoint = config('services.bleyt.fund_card_endpoint');

    $dataSupplied = [
        'amount' => $request->amount,
        'customerId' => $request->user()->bleyt_customer_id
      ];

    $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint, $dataSupplied);
    BleytResponse::logToDB($endpoint, $dataSupplied, $response, $request->user());

      if ($response->ok()) {
        $receivedDetails = $response->object();
        return response()->json($receivedDetails, 201);
      }
      else{
        return response()->json($response->object(), $response->status());
      }


  }

  public function requestDebitCard(CardRequestValidation $request)
  {
    $card_request = $request->user()->pending_debit_card_requests()->updateOrCreate(
      [
        'payment_method' => request('payment_method'),
        'address' => request('address'),
      ],
      Arr::collapse(
        [
          $request->validated(),
          [
            'debit_card_request_status_id' => 1,
          ]
        ]
      )
    );

    $debit_card_type = DebitCardType::find($request->debit_card_type_id);

    event(new DebitCardRequested($debit_card_type));

    return response()->json($card_request, 201);
  }

  public function activateCardUserDebitCard(CardActivationValidation $request)
  {
    $debit_card = DebitCard::find($request->card_id);
    if ($debit_card->activated_at) {
      throw ValidationException::withMessages(['auth_code' => "Card already activated"])->status(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    /**
     * Test csc
     */
    // if (Hash::check($request->csc, $debit_card->csc)) {
    if ($request->csc == $debit_card->csc) {
      DB::beginTransaction();

      $debit_card->is_user_activated = true;
      $debit_card->debit_card_request->debit_card_request_status_id = DebitCardRequestStatus::delivered_id();
      $debit_card->debit_card_request->save();
      $debit_card->save();

      if (!is_null($debit_card->sales_rep_id)) {
        $debit_card->sales_rep->activities()->create([
          'activity' => auth()->user()->email . ' has just successfully activated his new credit card'
        ]);
      }

      // Create bleyt wallet
      $rsp = $request->user()->createBleytWallet($debit_card, $request->bvn);
      if (!$rsp->status)  return response()->json(['message' => $rsp->message], 400);
      $bleyt_wallet_id = $rsp->bleyt_wallet_id;

      // Link card to bleyt wallet
      $rsp = $request->user()->linkCardToBleytWallet($debit_card, $request->bvn);
      if (!$rsp->status)  return response()->json(['message' => $rsp->message], 403);

      //save user's BVN. At this point bleyt should have validated it for us
      $request->user()->bvn = $request->bvn;
      $request->user()->save();

      //update user's bleyt wallet profile
      $dataSupplied = [
        'customerId' => $bleyt_wallet_id,
        'firstName' => $request->user()->first_name,
        'lastName' => $request->user()->last_name,
        'email' => $request->user()->email,
        'phoneNumber' => $request->user()->phone,
      ];

      $response = Http::withToken(config('services.bleyt.secret_key'))->put('https://api.bleyt.com/v1/customer/' . $bleyt_wallet_id, $dataSupplied);
      BleytResponse::logToDB('https://api.bleyt.com/v1/customer/' . $bleyt_wallet_id, $dataSupplied, $response, $request->user());

      // DB::commit();

      event(new DebitCardActivated);

      return response()->json(['message' => 'Card Activated'], 204);
    } else {
      return response()->json(['message' => 'Invalid CSC'], 403);
    }
  }

  public function deactivateCardUserDebitCard(CardDeactivationValidation $request)
  {
    $debit_card = DebitCard::find($request->card_id);
    $debit_card->is_suspended = true;
    $debit_card->save();

    try {
      return Mail::to(config('app.email'))->send(new CardBlockRequest($request->user(), $request->deactivation_reason));
    } catch (\Throwable $th) {
      ErrLog::notifyAdmin($request->user(), $th, 'User requested card block but details not sent via mail');
    }
    return response()->json(['message' => 'Card Blocked'], 204);
  }

  public function trackCardUserDebitCard()
  {
    $current_request_id = optional(auth()->user()->pending_debit_card_requests)->debit_card_request_status_id;
    $status = collect(DebitCardRequestStatus::all())->reject(function ($status) use ($current_request_id) {
      return $status->id > $current_request_id;
    });
    // $status = collect(DebitCardRequestStatus::all())->merge(['current' => $card_request->debit_card_request_status->id]);
    return response()->json(['status' => $status], 200);
  }

  /**
   * ! Admin Route Methods
   */
  public function getDebitCards($rep = null)
  {
    if (auth('admin')->check()) {
      $debit_cards = DebitCard::all();
    } else if (auth('sales_rep')->check()) {
      $debit_cards = auth('sales_rep')->user()->assigned_debit_cards()->get();
    } else if (auth('card_admin')->check()) {
      $debit_cards = DebitCard::pendingAdminActivation()->get();
    } else if (auth('normal_admin')->check()) {
      $debit_cards = DebitCard::all();
    } else {
      $debit_cards = collect([]);
    }
    return (new AdminDebitCardTransformer)->collectionTransformer($debit_cards, 'transformForAdminViewDebitCards');
  }

  public function createDebitCard(DebitCardCreationValidation $request, CardUser $user)
  {
    if (DebitCard::exists($request->card_number)) {
      return generate_422_error([
        'card_number' => ['That card already exists in the Database']
      ]);
    }
    try {
      DB::beginTransaction();

      $debit_card = $user->debit_cards()->create($request->all());

      ActivityLog::notifyAdmins(auth()->user()->email . ' created new Debit card ' . $debit_card->card_number);

      DB::commit();
      return response()->json(['rsp' => $debit_card], 201);
    } catch (\Throwable $e) {
      if (app()->environment() == 'local') {
        return response()->json(['error' => $e->getMessage()], 500);
      }
      return response()->json(['rsp' => 'error occurred'], 500);
    }
  }

  public function toggleDebitCardSuspendStatus(DebitCard $debit_card)
  {
    $debit_card->is_suspended = !$debit_card->is_suspended;
    $debit_card->save();

    ActivityLog::notifyAdmins(auth()->user()->email . ' changed the suspension state of debit card ' . $debit_card->card_number);

    return response()->json([], 204);
  }

  public function activateDebitCard(DebitCard $debit_card)
  {
    if ($debit_card->is_user_activated) {
      $debit_card->is_admin_activated = true;
      $debit_card->activated_at = now();
      $debit_card->save();

      ActivityLog::notifyAdmins(auth()->user()->email . ' activated debit card ' . $debit_card->card_number);

      return response()->json([], 204);
    } else {
      return response()->json(['message' => 'User has not activated card'], 403);
    }
  }

  public function assignDebitCard(DebitCard $debit_card)
  {
    if (!request('email')) {
      return generate_422_error(['email' => ['Email field is required']]);
    }

    if ($debit_card->sales_rep_id) {
      return generate_422_error(['rep' => ['Card assigned to another sales rep already']]);
    }

    try {
      $sales_rep = SalesRep::where('email', request('email'))->firstOrFail();
    } catch (ModelNotFoundException $e) {
      return generate_422_error(['rep' => ['Sales rep not found']]);
    }

    $debit_card->sales_rep_id = $sales_rep->id;
    $debit_card->assigned_by = auth()->id();
    $debit_card->save();

    ActivityLog::notifyAdmins(auth()->user()->email . ' assigned debit card ' . $debit_card->card_number . ' to ' . $sales_rep->email);

    return response()->json([], 204);
  }

  public function allocateDebitCard(DebitCard $debit_card)
  {
    /** Make sure they supply an email */
    if (!request('email')) {
      return generate_422_error([
        'email' => ['Email field is required']
      ]);
    }

    /** Make sure the card has been assigned to a sales rep */
    if (!$debit_card->sales_rep) {
      return response()->json(['message' => 'Unassigned card'], 423);
    }

    /** Get the user associated with that email or return a validation error */
    try {
      $card_user = CardUser::where('email', request('email'))->firstOrFail();
    } catch (ModelNotFoundException $e) {
      return generate_422_error(['user' => ['No such user found']]);
    }

    /** Check if the user has a pending existent card request. return a validation error if they do */
    if ($card_user->has_card_request()) {
      return generate_422_error(['err' => ['User already has a pending card request. Attend to that instead']]);
    }

    /** Create a request for the user that the user and we can use to track payment for this card */
    Model::unguard();
    $card_user->debit_card_requests()->create([
      'sales_rep_id' => auth('sales_rep')->id(),
      'debit_card_request_status_id' => DebitCardRequestStatus::delivered_id(),
      'debit_card_type_id' => $debit_card->debit_card_type->id,
      'debit_card_id' => $debit_card->id,
      'payment_method' => 'Direct Sales',
      'last_updated_by' => auth('sales_rep')->id(),
      'phone' => $card_user->phone,
      'address' => $card_user->address  ?? 'N/A',
      'zip' => $card_user->zip ?? 'N/A',
      'city' => $card_user->city ?? 'N/A',
      'is_paid' => true,
    ]);
    Model::reguard();

    /** alocate the card to the user */
    $debit_card->update([
      'card_user_id' => $card_user->id
    ]);

    /** record activity */
    ActivityLog::notifyAdmins(auth()->user()->email . ' allocated card ' . $debit_card->card_number . ' to user: ' . $card_user->email);

    return response()->json([], 204);
  }

  public function deleteDebitCard(Request $request, DebitCard $debit_card)
  {
    try {
      $debit_card->delete();
    } catch (\Throwable $th) {
      ErrLog::notifyAdmin($request->user(), $th, 'Unable to delete debit card');
      dd($th);
    }
    return response()->json(['rsp' => true], 204);
  }

  public function showFullPANNumber(DebitCard $debit_card)
  {
    ActivityLog::notifyAdmins(auth()->user()->email . ' accessed the full PAN number of card ' . $debit_card->card_number);

    return response()->json(['full_pan' => $debit_card->full_pan_number], 200);
  }

  /**
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeBleytUnactivated($query)
  {
    return $query->whereIsBleytActivated(false)->whereNotNull('bleyt_wallet_id');
  }

  public function scopeTitaniumBlack($query)
  {
    return $query->whereDebitCardTypeId(1);
  }

  protected static function boot()
  {
    parent::boot();

    static::saved(function (self $debitCard) {
      Cache::forget('debit-cards');
    });

    static::deleting(function (self $debitCard) {
      Cache::forget('debit-cards');
      $debitCard->debit_card_request()->delete();
      $debitCard->debit_card_transactions()->delete();
      $debitCard->debit_card_request()->delete();
      $debitCard->debit_card_funding_request()->delete();
    });
  }
}
