<?php

namespace App\Modules\Admin\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Modules\Admin\Models\Voucher;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\Merchant;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Modules\Accountant\Events\MerchantLoanPaid;
use App\Modules\Admin\Events\UserVoucherManualDebit;
use App\Modules\Accountant\Events\MerchantRequestDebit;
use App\Modules\Accountant\Events\MerchantTransactionMarkedAsPaid;
use App\Modules\Accountant\Events\UserApprovesMerchantTransaction;
use App\Modules\CardUser\Http\Requests\MakeVoucherRepaymentValidation;
use App\Modules\CardUser\Transformers\CardUserMerchantTransactionTransformer;

class MerchantTransaction extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'amount',
    'card_user_id',
    'merchant_id',
    'trans_type',
    'voucher_id',
    'description'
  ];

  public function merchant()
  {
    return $this->belongsTo(Merchant::class);
  }

  public function card_user()
  {
    return $this->belongsTo(CardUser::class);
  }

  public function voucher()
  {
    return $this->belongsTo(Voucher::class);
  }

  static function merchantRoutes()
  {
    Route::group(['namespace' => '\App\Modules\Admin\Models', 'middleware' => 'web'], function () {
      Route::get('{user}/merchant-transactions', 'MerchantTransaction@getAllCardUserMerchantTransactions')->name('user.merchant-transactions')->middleware('auth:admin');
      Route::get('merchants/{merchant}/transactions', 'MerchantTransaction@getAllMerchantTransactions')->name('merchant.transactions')->middleware('auth:admin');
      Route::get('/merchant-login', 'MerchantTransaction@showMerchantLoginForm')->name('merchants.login');
      Route::post('/validate-merchant-code', 'MerchantTransaction@validateMerchantCode');
      Route::post('merchant-transaction/create', 'MerchantTransaction@merchantDebitVoucher');
      Route::post('merchant-transaction/{card_user}/create', 'MerchantTransaction@manualVoucherDebit')->middleware('auth:admin');
      Route::patch('merchant-transactions/{trans}/paid', 'MerchantTransaction@markMerchantTransactionAsPaid')->middleware('auth:admin');
      Route::match(['get', 'post'], '/process-merchant-transaction/{trans_id}', 'MerchantTransaction@processMerchantTransaction');
    });

    Route::group(['namespace' => '\App\Modules\Admin\Models', 'prefix' => 'api/v1'], function () {
      Route::get('merchant-transactions', 'MerchantTransaction@merchantViewTransactions'); //->middleware('auth:api');
      Route::get('merchant-transactions/pending', 'MerchantTransaction@viewPendingVoucherDebitTransaction')->middleware('auth:card_user');
      Route::put('merchant-transaction/{merchant_transaction}/approve', 'MerchantTransaction@approveVoucherDebitTransaction')->middleware('auth:card_user');
      Route::delete('merchant-transaction/{merchant_transaction}/cancel', 'MerchantTransaction@cancelVoucherDebitTransaction')->middleware('auth:card_user');
      Route::post('merchant-loan/repay', 'MerchantTransaction@repayVoucherLoan')->middleware('auth:card_user');
    });
  }

  public function showMerchantLoginForm()
  {
    return view('merchants');
  }

  public function getAllCardUserMerchantTransactions(Request $request,  ?CardUser $user)
  {
    if (Auth::admin() && $user) {
      return $user->merchant_transactions->load('merchant:id,name');
    }
  }

  public function getAllMerchantTransactions(Request $request,  ?Merchant $merchant)
  {
    if (Auth::admin() && $merchant) {
      return $merchant->merchant_transactions;
    }
  }

  public function validateMerchantCode(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'merchant_code' => 'required|alpha_dash|exists:merchants,unique_code',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    return response()->json('', 204);
  }

  public function markMerchantTransactionAsPaid(self $trans)
  {

    event(new MerchantTransactionMarkedAsPaid($trans));

    $trans->is_merchant_paid = true;
    $trans->save();

    return response()->json([], 204);
  }

  /**
   * This route gets polled by the merchant page as they wait for the user to approve their debit requests
   *
   * @param int $trans_id
   *
   * @return JsonResponse (201 if approved, 200 if not yet approved, 205 if transaction not found e.g declined (deleted))
   */
  public function processMerchantTransaction($trans_id)
  {
    if (request()->isMethod('GET')) {
      $merchant_transaction = MerchantTransaction::findOrFail($trans_id);
      return view('process-merchant-transaction', compact('merchant_transaction'));
    } else if (request()->isMethod('POST')) {
      $merchant_transaction = MerchantTransaction::find($trans_id);
      if (is_null($merchant_transaction)) {
        return response()->json('', 205);
      } else {
        if ($merchant_transaction->trans_type == 'debit') {
          return response()->json('', 201);
        } else {
          return response()->json('', 200);
        }
      }
    }
  }

  /**
   * ! Merchant Routes
   */

  public function merchantDebitVoucher(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'voucher_code' => 'required|alpha_dash|exists:vouchers,code',
      'amount' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    /** Find the voucher */
    $voucher = Voucher::where('code', $request->voucher_code)->firstOrFail();
    $merchant = Merchant::where('unique_code', $request->merchant_code)->firstOrFail();

    /** Check if the Voucher is assigned */
    if (intval($voucher->card_user_id) <= 0) {
      return response()->json('Invalid voucher selected', 422);
    }

    /**Check the balance and the amount */
    if ($voucher->amount < $request->amount) {
      return response()->json('Insufficient balance in your voucher', 422);
    }
    /** Use the voucher code to create a new transaction */
    $trans = $voucher->merchant_transactions()->create([
      'amount' => $request->amount,
      'card_user_id' => $voucher->card_user_id,
      'merchant_id' => $merchant->id,
      'trans_type' => 'debit request'
    ]);

    event(new MerchantRequestDebit($voucher, $merchant));

    if ($request->ajax() || $request->expectsJson()) {
      // return (new CardUserMerchantTransactionTransformer)->transform($trans);
      return response()->json((new CardUserMerchantTransactionTransformer)->transform($trans), 201);
    } else {
      return back()->withSuccess('Debit request created');
    }
  }

  public function manualVoucherDebit(Request $request, CardUser $card_user)
  {
    $validator = Validator::make($request->all(), [
      'merchant_code' => 'required|alpha_dash|exists:merchants,unique_code',
      'amount' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors()->first(), 422);
    }

    /** Find the voucher */
    $voucher = $card_user->active_voucher ?? abort(422, 'User has no voucher');
    $merchant = Merchant::where('unique_code', $request->merchant_code)->first();

    /**Check the balance and the amount */
    if ($voucher->amount < $request->amount) {
      return response()->json('Insufficient balance in user voucher', 422);
    }

    /** Use the voucher code to create a new transaction */
    $trans = $voucher->merchant_transactions()->create([
      'amount' => $request->amount,
      'card_user_id' => $voucher->card_user_id,
      'merchant_id' => $merchant->id,
      'description' => 'Automatic voucher debit for ' . $merchant->name,
      'trans_type' => 'debit'
    ]);

    event(new UserVoucherManualDebit($voucher->code, $merchant->name, $card_user, $request->amount));

    if ($request->ajax() || $request->expectsJson()) {
      return response()->json([], 201);
    } else {
      return back()->withSuccess('Voucher Debited');
    }
  }

  public function merchantViewTransactions(Request $request)
  {
    $merchant = Merchant::where('unique_code', request('merchant_code'))->firstOrFail();
    return (new CardUserMerchantTransactionTransformer)->collectionTransformer($merchant->merchant_transactions, 'transformForMerchantView'); //['Merchant wants to view all his transactions along with the user name'];
  }

  /**
   * ! Card User Routes
   */

  public function viewPendingVoucherDebitTransaction()
  {
    $trans = auth()->user()->unapproved_merchant_transactions;
    if ($trans) {
      return (new CardUserMerchantTransactionTransformer)->transform($trans);
    } else {
      return [];
    }
  }

  public function approveVoucherDebitTransaction($merchant_transaction)
  {
    $merchant_trans = MerchantTransaction::find($merchant_transaction);

    if ($merchant_trans->trans_type == 'debit') {
      abort(403, 'Already approved');
    }

    event(new UserApprovesMerchantTransaction($merchant_trans));

    $merchant_trans->trans_type = 'debit';
    $merchant_trans->save();

    return response()->json([], 204);
  }

  public function cancelVoucherDebitTransaction($merchant_transaction)
  {
    /**
     * ! check if its a pending transaction first
     */
    $mer = MerchantTransaction::destroy($merchant_transaction);

    return response()->json([], 204);
  }

  public function repayVoucherLoan(MakeVoucherRepaymentValidation $request)
  {
    $voucher = auth()->user()->active_voucher;
    DB::beginTransaction();
    auth()->user()->merchant_transactions()->create([
      'amount' => $request->amount,
      'voucher_id' => $voucher->id,
      'trans_type' => 'repayment'
    ]);

    event(new MerchantLoanPaid($voucher->code));

    DB::commit();

    return response()->json(['rsp' => true], 201);
  }
}
