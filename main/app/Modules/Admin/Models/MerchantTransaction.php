<?php

namespace App\Modules\Admin\Models;

use Illuminate\Support\Facades\DB;
use App\Modules\Admin\Models\Voucher;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\Merchant;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Notifications\VoucherPaid;
use App\Modules\CardUser\Notifications\VoucherApproved;
use App\Modules\Admin\Http\Requests\MerchantDebitVoucherValidation;
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
		Route::group(['namespace' => '\App\Modules\Admin\Models', 'prefix' => 'api/v1'], function () {
			Route::post('merchant-transaction/create', 'MerchantTransaction@merchantDebitVoucher')->middleware('web'); //->middleware('auth.basic:merchant');
			Route::get('merchant-transactions', 'MerchantTransaction@merchantViewTransactions'); //->middleware('auth.basic:merchant');
			Route::get('merchant-transactions/pending', 'MerchantTransaction@viewPendingVoucherDebitTransaction')->middleware('auth:card_user');
			Route::put('merchant-transaction/{merchant_transaction}/approve', 'MerchantTransaction@approveVoucherDebitTransaction')->middleware('auth:card_user');
			Route::post('merchant-loan/repay', 'MerchantTransaction@repayVoucherLoan')->middleware('auth:card_user');
		});
	}


	/**
	 * ! Merchant Routes
	 */

	public function merchantDebitVoucher(MerchantDebitVoucherValidation $request)
	{
		// return $request->voucher_code;
		/** Find the voucher */
		$voucher = Voucher::where('code', $request->voucher_code)->firstOrFail();
		$merchant = Merchant::where('unique_code', $request->merchant_code)->firstOrFail();

		/** Check if the Voucher is assigned */
		if (intval($voucher->card_user_id) <= 0) {
			abort(403, 'Invalid voucher selected');
		}
		/**Check the balance and the amount */
		if ($voucher->amount < $request->amount) {
			return response()->json(['message' => 'Insufficient balance in your voucher'], 422);
		}
		/** Use the voucher code to create a new transaction */
		$trans = $voucher->merchant_transactions()->create([
			'amount' => $request->amount,
			'card_user_id' => $voucher->card_user_id,
			'merchant_id' => $merchant->id,
			'trans_type' => 'debit request'
		]);

		// ActivityLog::logUserActivity($merchant->name . ' requests voucher debit from ' . optional($voucher->card_user)->email . '. Voucher Number: ' . $voucher->code);

		if ($request->ajax()) {
			return (new CardUserMerchantTransactionTransformer)->transform($trans, 'transform');
		} else {
			return back()->withSuccess('Debit request created');
		}
	}

	public function merchantViewTransactions()
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
		$mer = MerchantTransaction::find($merchant_transaction);

		if ($mer->trans_type == 'debit') {
			abort(403, 'Already approved');
		}

		$mer->trans_type = 'debit';
		$mer->save();

		ActivityLog::logUserActivity(auth()->user()->email . ' approves voucher debit from ' . optional($mer->merchant)->name . '. Voucher Number: ' . optional($mer->voucher)->code);
		ActivityLog::notifyAccountOfficers(auth()->user()->email . ' approves voucher debit from ' . optional($mer->merchant)->name . '. Voucher Number: ' . optional($mer->voucher)->code);
		ActivityLog::notifyAdmins(auth()->user()->email . ' approves voucher debit from ' . optional($mer->merchant)->name . '. Voucher Number: ' . optional($mer->voucher)->code);

		auth('card_user')->user()->notify(new VoucherApproved($mer->name));

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

		ActivityLog::logUserActivity(auth()->user()->email . ' repays merchant credit. Voucher Number: ' . $voucher->code . '. Amount: ' . $request->amount);
		ActivityLog::notifyAccountants(auth()->user()->email . ' repays merchant credit. Voucher Number: ' . $voucher->code . '. Amount: ' . $request->amount);
		ActivityLog::notifyAccountOfficers(auth()->user()->email . ' repays merchant credit. Voucher Number: ' . $voucher->code . '. Amount: ' . $request->amount);
		ActivityLog::notifyAdmins(auth()->user()->email . ' repays merchant credit. Voucher Number: ' . $voucher->code . '. Amount: ' . $request->amount);
		ActivityLog::notifyNormalAdmins(auth()->user()->email . ' repays merchant credit. Voucher Number: ' . $voucher->code . '. Amount: ' . $request->amount);

		auth('card_user')->user()->notify(new VoucherPaid($request->amount));

		DB::commit();

		return response()->json(['rsp' => true], 201);
	}
}
