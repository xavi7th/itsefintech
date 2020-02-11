<?php

namespace App\Modules\CardUser\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Models\DebitCardType;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\CardUser\Models\DebitCardTransaction;
use App\Modules\CardUser\Models\DebitCardRequestStatus;
use App\Modules\CardUser\Models\DebitCardFundingRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Admin\Transformers\AdminDebitCardTransformer;
use App\Modules\CardUser\Http\Requests\CardRequestValidation;
use App\Modules\Admin\Http\Requests\DebitCardCreationValidation;
use App\Modules\CardUser\Http\Requests\CardActivationValidation;
use App\Modules\CardUser\Transformers\CardUserDebitCardTransformer;
use Watson\Rememberable\Rememberable;
use App\Modules\CardUser\Notifications\DebitCardRequested;
use App\Modules\CardUser\Notifications\DebitCardActivated;

class DebitCard extends Model
{
	use SoftDeletes, Rememberable;

	protected $fillable = [
		'card_number', 'month', 'year', 'csc', 'sales_rep_id', 'card_user_id', 'debit_card_type_id'
	];

	protected $rememberFor = 10;
	protected $rememberCachePrefix = 'debit-cards';

	protected $appends = ['exp_date'];

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

	static function cardUserRoutes()
	{

		Route::group(['prefix' => 'card', 'middleware' => ['auth:card_user', 'card_users', 'verified_card_users']], function () {
			Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
				Route::get('/list', 'DebitCard@getCardUserDebitCards');
				Route::post('/new', 'DebitCard@requestDebitCard');
				Route::put('/activate', 'DebitCard@activateCardUserDebitCard');
				Route::get('/status', 'DebitCard@trackCardUserDebitCard');
				Route::get('/{debit_card}', 'DebitCard@getCardUserCardDetails');
			});
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {

			Route::get('debit-cards/{rep?}', 'DebitCard@getDebitCards')->middleware('auth:admin');

			Route::post('debit-card/create', 'DebitCard@createDebitCard')->middleware('auth:admin');

			Route::put('debit-card/{debit_card}/suspension', 'DebitCard@toggleDebitCardSuspendStatus')->middleware('auth:admin');

			Route::put('debit-card/{debit_card}/activate', 'DebitCard@activateDebitCard')->middleware('auth:admin');

			Route::put('debit-card/{debit_card}/assign', 'DebitCard@assignDebitCard')->middleware('auth:admin');

			Route::put('debit-card/{debit_card}/allocate', 'DebitCard@allocateDebitCard')->middleware('auth:admin');

			Route::delete('debit-card/{debit_card}/delete', 'DebitCard@deleteDebitCard')->middleware('auth:admin');

			Route::get('debit-card/{debit_card}/pan', 'DebitCard@showFullPANNumber')->middleware('auth:admin');
		});
	}

	/**
	 * ! Card User route methods
	 */
	public function getCardUserDebitCards()
	{
		return response()->json((new CardUserDebitCardTransformer)->collectionTransformer(auth('card_user')->user()->debit_cards, 'transformForCardList'), 200);
	}

	public function getCardUserCardDetails(DebitCard $debit_card)
	{
		return response()->json((new CardUserDebitCardTransformer)->transformForCardDetails($debit_card), 200);
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
					$request->all(),
					[
						'debit_card_request_status_id' => 1,
					]
				]
			)
		);

		$debit_card_type = DebitCardType::find($request->debit_card_type_id);

		ActivityLog::logUserActivity(auth()->user()->email . ' made a debit card request');

		auth()->user()->notify(new DebitCardRequested($debit_card_type));

		return response()->json($card_request, 201);
	}

	public function activateCardUserDebitCard(CardActivationValidation $request)
	{

		$debit_card = DebitCard::find($request->card_id);
		if ($debit_card->is_card_activated) {
			return generate_422_error(['card_activation' => 'Card already activated']);
		}
		/**
		 * Test csc
		 */
		// if (Hash::check($request->csc, $debit_card->csc)) {
		if ($request->csc == $debit_card->csc) {
			$debit_card->is_user_activated = true;
			$debit_card->debit_card_request->debit_card_request_status_id = DebitCardRequestStatus::orderByDesc('id')->first()->id;
			$debit_card->debit_card_request->save();
			$debit_card->save();

			ActivityLog::logUserActivity(auth()->user()->email . ' has just successfully activated his new credit card');
			auth()->user()->notify(new DebitCardActivated);

			return response()->json(['message' => 'Card Activated'], 204);
		} else {
			return response()->json(['message' => 'Invalid CSC'], 403);
		}
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
		if (is_null($rep)) {
			$debit_cards = DebitCard::withTrashed()->get();
		} else {
			$debit_cards = SalesRep::find($rep)->assigned_debit_cards()->withTrashed()->get();
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

			ActivityLog::logAdminActivity(auth()->user()->email . ' created new Debit card ' . $debit_card->card_number);

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

		ActivityLog::logAdminActivity(auth()->user()->email . ' changed the suspension state of debit card ' . $debit_card->card_number);

		return response()->json([], 204);
	}

	public function activateDebitCard(DebitCard $debit_card)
	{
		if ($debit_card->is_user_activated) {
			$debit_card->is_admin_activated = true;
			$debit_card->activated_at = now();
			$debit_card->save();

			ActivityLog::logAdminActivity(auth()->user()->email . ' activated debit card ' . $debit_card->card_number);

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

		ActivityLog::logAdminActivity(auth()->user()->email . ' assigned debit card ' . $debit_card->card_number . ' to ' . $sales_rep->email);

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
			'debit_card_request_status_id' => 1,
			'debit_card_id' => $debit_card->id,
			'payment_method' => 'Sales Rep',
			'last_updated_by' => auth('sales_rep')->id(),
			'phone' => $card_user->phone,
			'address' => $card_user->address  ?? 'N/A',
			'zip' => $card_user->zip ?? 'N/A',
			'city' => $card_user->city ?? 'N/A',
		]);
		Model::reguard();

		/** alocate the card to the user */
		$debit_card->update([
			'card_user_id' => $card_user->id
		]);

		/** record activity */
		ActivityLog::logAdminActivity(auth()->user()->email . ' allocated card ' . $debit_card->card_number . ' to user: ' . $card_user->email);

		return response()->json([], 204);
	}

	public function deleteDebitCard(DebitCard $debit_card)
	{
		return;
		$debit_card->delete();
		return response()->json(['rsp' => true], 204);
	}

	public function showFullPANNumber(DebitCard $debit_card)
	{
		ActivityLog::logAdminActivity(auth()->user()->email . ' accessed the full PAN number of card ' . $debit_card->card_number);

		return response()->json(['full_pan' => $debit_card->full_pan_number], 200);
	}
}
