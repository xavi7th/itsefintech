<?php

namespace App\Modules\Admin\Models;

use App\Modules\Admin\Models\Voucher;
use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\Merchant;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\Admin\Http\Requests\MerchantDebitVoucherValidation;
use App\Modules\CardUser\Http\Requests\MakeVoucherRepaymentValidation;
use App\Modules\CardUser\Transformers\CardUserMerchantTransactionTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

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
			Route::post('merchant-transaction/create', 'MerchantTransaction@merchantDebitVoucher'); //->middleware('auth.basic:merchant');
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

		return (new CardUserMerchantTransactionTransformer)->transform($trans, 'transform');
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

		return response()->json([], 204);
	}

	public function repayVoucherLoan(MakeVoucherRepaymentValidation $request)
	{
		auth()->user()->merchant_transactions()->create([
			'amount' => $request->amount,
			'voucher_id' => auth()->user()->active_voucher->id,
			'trans_type' => 'repayment'
		]);
		return response()->json(['rsp' => true], 201);
	}
}
