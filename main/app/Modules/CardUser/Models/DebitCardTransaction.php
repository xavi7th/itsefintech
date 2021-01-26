<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Notifications\CardDebited;
use App\Modules\CardUser\Http\Requests\CreateCardTransactionValidation;
use App\Modules\CardUser\Transformers\DebitCardTransactionsTransformer;

/**
 * App\Modules\CardUser\Models\DebitCardTransaction
 *
 * @property int $id
 * @property int $card_user_id
 * @property int $debit_card_id
 * @property float $amount
 * @property string $trans_description
 * @property string $trans_category
 * @property string $trans_type
 * @property string|null $paystack_id
 * @property string|null $paystack_ref
 * @property string|null $paystack_message
 * @property string|null $quickteller_req_ref
 * @property string|null $quickteller_trans_ref
 * @property string|null $quickteller_res_code
 * @property int $is_unresolved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Modules\CardUser\Models\CardUser $card_user
 * @property-read \App\Modules\CardUser\Models\DebitCard $debit_card
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereCardUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereDebitCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereIsUnresolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction wherePaystackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction wherePaystackMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction wherePaystackRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereQuicktellerReqRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereQuicktellerResCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereQuicktellerTransRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereTransCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereTransDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereTransType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\DebitCardTransaction onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\DebitCardTransaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CardUser\Models\DebitCardTransaction withoutTrashed()
 */
class DebitCardTransaction extends Model
{
  use SoftDeletes;

	protected $fillable = [
		'card_user_id',
		'debit_card_id',
		'amount',
		'trans_description',
		'trans_category',
		'trans_type',
		'paystack_id',
		'paystack_ref',
		'paystack_message',
		'quickteller_req_ref',
		'quickteller_trans_ref',
		'quickteller_res_code',
	];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function debit_card()
	{
		return $this->belongsTo(DebitCard::class);
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models', 'middleware' => ['verified_card_users']], function () {
			// Route::get('debit-card-transactions', 'DebitCardTransaction@getCardUserCardTransactions')->middleware('auth:card_user');
			Route::get('debit-card-transactions/{debit_card}/{type?}', 'DebitCardTransaction@getDebitCardTransactions')->middleware('auth:card_user');
			Route::post('debit-card-transaction/create', 'DebitCardTransaction@createCardTransaction')->middleware('auth:card_user');
		});
	}

	/**
	 * ! Card User route methods
	 */

	public function getCardUserCardTransactions()
	{
		return (new DebitCardTransactionsTransformer)->collectionTransformer(auth()->user()->debit_card_transactions, 'transform');
	}

	public function getDebitCardTransactions(Request $request, DebitCard $debit_card, $type = 'all')
	{

    $endpoint = config('services.bleyt.view_transactions_endpoint');

    $dataSupplied = [
      'customerId' => $request->user()->bleyt_wallet_id,
      'type' => $type,
      'page' => $request->page
    ];

    $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint, $dataSupplied);
    BleytResponse::logToDB($endpoint, $dataSupplied, $response, $request->user());

    if ($response->ok()) {
      $receivedDetails = $response->json()['transactions'];
      return (new DebitCardTransactionsTransformer)->collectionTransformer(collect($receivedDetails), 'transform');
    }
    else{
      return response()->json(['message' => $response->message], 400);
    }
	}

	public function createCardTransaction(CreateCardTransactionValidation $request)
	{
		$card_trans = $request->user()->debit_card_transactions()->create($request->all());

		ActivityLog::logUserActivity(auth()->user()->email . ' carried out a transaction on his card');
		ActivityLog::notifyCardAdmins(auth()->user()->email . ' carried out a transaction on his card');
		ActivityLog::notifyAdmins(auth()->user()->email . ' carried out a transaction on his card');
		ActivityLog::notifyAccountOfficers(auth()->user()->email . ' carried out a transaction on his card');

		$request->user()->notify(new CardDebited($request->all()));

		return response()->json((new DebitCardTransactionsTransformer)->transform($card_trans), 201);
	}
}
