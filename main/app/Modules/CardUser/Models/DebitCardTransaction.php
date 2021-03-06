<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Notifications\CardDebited;
use App\Modules\CardUser\Http\Requests\MakeBankTransferValidation;
use App\Modules\CardUser\Http\Requests\MakeCapitalXTransferValidation;
use App\Modules\CardUser\Http\Requests\CreateCardTransactionValidation;
use App\Modules\CardUser\Transformers\DebitCardTransactionsTransformer;
use App\Modules\CardUser\Http\Requests\ResolveBankAccountNameValidation;

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
		Route::group(['middleware' => ['verified_card_users']], function () {
      Route::get('debit-card-transactions', [self::class, 'getCardUserCardTransactions'])->middleware('auth:card_user');

      Route::get('debit-card-transactions/bank-list', [self::class, 'getListOfBanks'])->name('user.get-banks')->middleware('auth:card_user');
      Route::get('debit-card-transactions/resolve-account-name', [self::class, 'resolveAccountName'])->name('user.resolve_acc_num')->middleware('auth:card_user');
			Route::post('debit-card-transactions/make-bank-transfer', [self::class, 'transferToBankAccount'])->middleware('auth:card_user');
			Route::post('debit-card-transactions/transfer-to-capitalx-account', [self::class, 'transferToCapitalXAccount'])->middleware('auth:card_user');

      Route::get('debit-card-transactions/{debit_card}/{type?}', [self::class, 'getDebitCardTransactions'])->middleware('auth:card_user');
      Route::post('debit-card-transaction/create', [self::class, 'createCardTransaction'])->middleware('auth:card_user');
		});
	}

	/**
	 * ! Card User route methods
	 */

	public function getCardUserCardTransactions(Request $request)
	{
		// return (new DebitCardTransactionsTransformer)->collectionTransformer(auth()->user()->debit_card_transactions, 'transform');

    $endpoint = config('services.bleyt.create_wallet_endpoint');

     $dataSupplied = [
        'firstName' => $request->user()->first_name,
        'lastName' => $request->user()->last_name,
        'email' => $request->user()->email,
        'phoneNumber' => $request->user()->phone,
        'dateOfBirth' => $request->user()->date_of_birth ?? now()->subYears(20)->toDateString(),
        'bvn' => $request->user()->plain_bvn ?? '',
      ];

      $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint, $dataSupplied);

      if ($response->ok()) {
        $receivedDetails = $response->object();
        $request->user()->bleyt_customer_id = $receivedDetails->customer->id;
        $request->user()->first_debit_card->bleyt_wallet_id = $receivedDetails->wallet->id;
        $request->user()->first_debit_card->save();
        $request->user()->save();
      }

      BleytResponse::logToDB($endpoint, $dataSupplied, $response, $request->user());

      print_r($response->status());
      print_r($request->user()->first_name);
      // print_r($response->object());

       $endpoint1 = config('services.bleyt.issue_card_endpoint');
    $endpoint2 = config('services.bleyt.activate_card_endpoint');

    /** @var CardUser */
    $cardUser = $request->user();

      /**
       * First supply card customer address
       */

      if (!$cardUser->hasAddress() || !$cardUser->plain_bvn) {
        print('Failed to activate card');
      }

      $dataSupplied1 = [
        'customerId' => $cardUser->bleyt_customer_id,
        'address1' => $cardUser->address . ' ' . $cardUser->city,
        'address2' => $cardUser->address . ' ' . $cardUser->city,
        'bvn' => $cardUser->plain_bvn
      ];

      $dataSupplied2 = [
        'customerId' => $cardUser->bleyt_customer_id,
        'bvn' => $cardUser->plain_bvn,
        'last6' => $debitCard = $cardUser->debit_cards()->titaniumBlack()->last6_digits,
      ];

      $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint1, $dataSupplied1);
      BleytResponse::logToDB($endpoint1, $dataSupplied1, $response, $cardUser);

      print_r([$cardUser->first_name => $response->object()]);

      if ($response->ok()) {
        $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint2, $dataSupplied2);
        BleytResponse::logToDB($endpoint2, $dataSupplied2, $response, $cardUser);

        $debitCard->is_bleyt_activated = true;
        $debitCard->save();

        print_r([$cardUser->first_name => $response->object()]);
      }

    print_r($request->user()->bleyt_customer_id);
    exit;

    $endpoint = config('services.bleyt.view_transactions_endpoint');
    $dataSupplied = [
      'customerId' => $request->user()->bleyt_customer_id,
      'type' => 'ALL',
      'page' => 1
    ];

    $response = Http::withToken(config('services.bleyt.secret_key'))->get($endpoint, $dataSupplied);
    BleytResponse::logToDB($endpoint, $dataSupplied, $response, $request->user());

    if ($response->ok()) {
      $receivedDetails = $response->json()['transactions'];
      return (new DebitCardTransactionsTransformer)->collectionTransformer(collect($receivedDetails), 'transform');
    }
    else{
      return response()->json(['message' => $response->body()], 400);
    }
	}

  public function getDebitCardTransactions(Request $request, DebitCard $debit_card, $type = 'ALL')
	{

    $endpoint = config('services.bleyt.view_transactions_endpoint');

    $dataSupplied = [
      'customerId' => $request->user()->bleyt_customer_id,
      'type' => $type,
      'page' => $request->page
    ];


    $response = Http::withToken(config('services.bleyt.secret_key'))->get($endpoint, $dataSupplied);
    BleytResponse::logToDB($endpoint, $dataSupplied, $response, $request->user());

    if ($response->ok()) {
      $receivedDetails = $response->json()['transactions'];
      return (new DebitCardTransactionsTransformer)->collectionTransformer(collect($receivedDetails), 'transform');
    }
    else{
      return response()->json(['message' => $response->body()], 400);
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

  public function getListOfBanks()
  {
    $endpoint = config('services.bleyt.banks_list_endpoint');

    $response = Http::withToken(config('services.bleyt.secret_key'))->get($endpoint);
    $receivedDetails = $response->object();

    if ($response->ok()) {
      return response()->json(['list_of_banks' => $receivedDetails->banks], 200);
    }
    else{
      return response()->json(['message' => $receivedDetails->message], 400);
    }
  }

  public function resolveAccountName(ResolveBankAccountNameValidation $request)
  {

    $endpoint = config('services.bleyt.reslove_bank_account_name_endpoint');

    $dataSupplied = [
      'sortCode' => $request->sort_code,
      'accountNumber' => $request->account_number,
    ];

    $response = Http::withToken(config('services.bleyt.secret_key'))->get($endpoint, $dataSupplied);
    BleytResponse::logToDB($endpoint, $dataSupplied, $response, $request->user());

    $receivedDetails = $response->object();

    if ($response->ok()) {
      return response()->json(['account_name' => $receivedDetails->account->accountName], 200);
    }
    else{
      return response()->json(['message' => $receivedDetails->message], 400);
    }

  }

  public function transferToBankAccount(MakeBankTransferValidation $request)
  {
    $endpoint = config('services.bleyt.wallet_to_bank_transfer_endpoint');

    $dataSupplied = [
      'sortCode' => $request->sort_code,
      'accountNumber' => $request->account_number,
      'accountName' => $request->account_name,
      'narration' => $request->narration,
      'amount' => $request->amount,
      'customerId' => $request->user()->bleyt_customer_id,
      'metadata' => [
        "customer_name" => $request->user()->first_name . ' ' . $request->user()->last_name,
        "capitalx_id" => $request->user()->id,
        "customer_email" => $request->user()->email
      ]
    ];

    $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint, $dataSupplied);
    BleytResponse::logToDB($endpoint, $dataSupplied, $response, $request->user());

    $receivedDetails = $response->object();

    if ($response->ok()) {
      return response()->json([
        'transfer_status' => (bool)$receivedDetails->transfer->success
        ], 201);
    }
    else{
      return response()->json(['message' => $receivedDetails->message], 400);
    }
  }

  public function transferToCapitalXAccount(MakeCapitalXTransferValidation $request)
  {
    /**
     * @var CardUser $receiver
     */
    $receiver = CardUser::whereEmail($request->email)->first();

    $endpoint = config('services.bleyt.wallet_to_wallet_transfer_endpoint');

    $dataSupplied = [
      'fromCustomerId' => $request->user()->bleyt_customer_id,
      'toCustomerId' => $receiver->bleyt_customer_id,
      'amount' => $request->amount,

    ];

    $response = Http::withToken(config('services.bleyt.secret_key'))->post($endpoint, $dataSupplied);
    BleytResponse::logToDB($endpoint, $dataSupplied, $response, $request->user());

    $receivedDetails = $response->object();

    if ($response->ok()) {
      return response()->json([
        'transfer_status' => (string)$receivedDetails->message
        ], 201);
    }
    else{
      return response()->json(['message' => $receivedDetails->message], 400);
    }
  }

}
