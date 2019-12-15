<?php

namespace App\Modules\CardUser\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Modules\Admin\Transformers\AdminDebitCardTransformer;
use App\Modules\Admin\Http\Requests\DebitCardCreationValidation;
use App\Modules\Admin\Models\ActivityLog;

class DebitCard extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'card_number', 'month', 'year', 'csc', 'sales_rep_id', 'card_user_id'
	];
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

	public function getExpDateAttribute()
	{
		return Carbon::createFromDate($this->year, $this->month + 1, 1);
	}

	public function getCardNumberAttribute($value)
	{
		// return decrypt($value);
		return 'ending in ' . substr(decrypt($value), -4);
	}

	public function setCardNumberAttribute($value)
	{
		$this->attributes['card_number'] = encrypt($value);
		$this->attributes['card_hash'] = static::hash($value);
	}

	public function setCscAttribute($value)
	{
		$this->attributes['csc'] = bcrypt($value);
	}

	static function routes()
	{
		Route::get('debit-cards/{rep?}', function ($rep = null) {
			if (is_null($rep)) {
				$debit_cards = DebitCard::withTrashed()->get();
			} else {
				$debit_cards = SalesRep::find($rep)->assigned_debit_cards()->withTrashed()->get();
			}
			return (new AdminDebitCardTransformer)->collectionTransformer($debit_cards, 'transformForAdminViewDebitCards');
		})->middleware('auth:admin');

		Route::post('debit-card/create', function (DebitCardCreationValidation $request, CardUser $user) {
			if (DebitCard::exists($request->card_number)) {
				return generate_422_error([
					'card_number' => ['That card already exists in the Database']
				]);
			}
			try {
				DB::beginTransaction();

				$debit_card = $user->debit_cards()->create($request->all());

				ActivityLog::logAdminActivity('Created new Debit card ' . $debit_card->card_number);

				DB::commit();
				return response()->json(['rsp' => $debit_card], 201);
			} catch (\Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::put('debit-card/{debit_card}/suspension', function (DebitCard $debit_card) {
			$debit_card->is_suspended = !$debit_card->is_suspended;
			$debit_card->save();
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::put('debit-card/{debit_card}/activate', function (DebitCard $debit_card) {
			$debit_card->is_admin_activated = true;
			$debit_card->save();
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::put('debit-card/{debit_card}/assign', function (DebitCard $debit_card) {
			if (!request('email')) {
				return generate_422_error(['email' => ['Email field is required']]);
			}
			$sales_rep = SalesRep::where('email', request('email'))->firstOrFail();
			$debit_card->update([
				'sales_rep_id' => $sales_rep->id
			]);
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::put('debit-card/{debit_card}/allocate', function (DebitCard $debit_card) {

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
			ActivityLog::logAdminActivity('Allocated card ' . $debit_card->card_number . ' to user: ' . $card_user->id);

			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::delete('debit-card/{debit_card}/delete', function (DebitCard $debit_card) {
			return;
			$debit_card->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
