<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Watson\Rememberable\Rememberable;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Http\Requests\RequestDebitCardFundingValidation;
use App\Modules\CardUser\Transformers\DebitCardFundingRequestTransformer;
use App\Modules\NormalAdmin\Transformers\AdminDebitCardFundingRequestTransformer;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Notifications\CardFundingRequested;
use App\Modules\Admin\Notifications\CardFundingProcessed;

/**
 * App\Modules\CardUser\Models\DebitCardFundingRequest
 *
 * @property int $id
 * @property int $card_user_id
 * @property int $debit_card_id
 * @property float $amount
 * @property int $is_funded
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Modules\CardUser\Models\CardUser $card_user
 * @property-read \App\Modules\CardUser\Models\DebitCard $debit_card
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest whereCardUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest whereDebitCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest whereIsFunded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\DebitCardFundingRequest withoutTrashed()
 * @mixin \Eloquent
 */
class DebitCardFundingRequest extends Model
{
	use SoftDeletes, Rememberable;

	protected $rememberFor = 30;

	protected $fillable = [
		'card_user_id',
		'amount',
	];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function debit_card()
	{
		return $this->belongsTo(DebitCard::class);
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('debit-card-funding-requests', 'DebitCardFundingRequest@getAllFundingRequests')->middleware('auth:admin,normal_admin,accountant');
			Route::put('debit-card-funding-request/{funding_request}/process', 'DebitCardFundingRequest@markProcessed')->middleware('auth:accountant');
		});
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models', 'middleware' => ['verified_card_users']], function () {
			Route::post('debit-card-funding-request/validate', 'DebitCardFundingRequest@validateDebitCardFunding')->middleware('auth:card_user');
			Route::post('debit-card-funding-request/create', 'DebitCardFundingRequest@requestDebitCardFunding')->middleware('auth:card_user');
			Route::get('debit-card-funding-request/status', 'DebitCardFundingRequest@checkDebitCardFundingStatus')->middleware('auth:card_user');
		});
	}

	/**
	 * ! Card User routes
	 */

	public function validateDebitCardFunding(RequestDebitCardFundingValidation $request)
	{
		return response()->json(['message' => true], 202);
	}

	public function requestDebitCardFunding(RequestDebitCardFundingValidation $request)
	{
		$fund_request = DebitCard::find($request->debit_card_id)->debit_card_funding_request()->create([
			'card_user_id' => auth()->user()->id,
			'amount' => $request->amount
		]);

		ActivityLog::logUserActivity(auth()->user()->email . ' requested card funds of ' . $request->amount);

		auth()->user()->notify(new CardFundingRequested($request->amount));

		return response()->json((new DebitCardFundingRequestTransformer)->transform($fund_request), 201);
	}

	public function checkDebitCardFundingStatus()
	{
		$funding_request = auth()->user()->debit_card_funding_request;
		if ($funding_request) {
			return (new DebitCardFundingRequestTransformer)->transform($funding_request);
		} else {
			return response()->json(['message' => 'User has no funding request'], 404);
		}
	}

	/**
	 * ! Admin Routes
	 */
	public function getAllFundingRequests()
	{
		if (auth('admin')->check()) {
			return (new AdminDebitCardFundingRequestTransformer)->collectionTransformer(DebitCardFundingRequest::withTrashed()->dontRemember()->get(), 'transformForViewAllRequests');
		} else if (auth('normal_admin')->check()) {
			return (new AdminDebitCardFundingRequestTransformer)->collectionTransformer(DebitCardFundingRequest::dontRemember()->get(), 'transformForViewAllRequests');
		} else if (auth('accountant')->check()) {
			return (new AdminDebitCardFundingRequestTransformer)->collectionTransformer(DebitCardFundingRequest::dontRemember()->get(), 'transformForViewAllRequests');
		}
	}

	public function markProcessed(DebitCardFundingRequest $funding_request)
	{
		$card_user = $funding_request->card_user;
		DB::beginTransaction();
		/**
		 * Create a credit transaction for this debit card
		 */
		$debit_card = new DebitCardTransaction();
		$debit_card->card_user_id = $funding_request->card_user_id;
		$debit_card->debit_card_id = $funding_request->debit_card_id;
		$debit_card->amount = $funding_request->amount;
		$debit_card->trans_description = 'Card funded';
		$debit_card->trans_category = 'Card funding';
		$debit_card->trans_type = 'credit';
		$debit_card->save();

		/**
		 * Mark the funding request as processed
		 */
		$funding_request->is_funded = true;
		$funding_request->save();

		ActivityLog::logAdminActivity(auth()->user()->email . ' marked ' . $card_user . '\'s funding request as processed');

		$card_user->notify(new CardFundingProcessed($funding_request->amount));

		DB::commit();
		return response()->json([], 204);
	}
}
