<?php

namespace App\Modules\Admin\Models;

use Illuminate\Support\Facades\DB;
use App\Modules\Admin\Models\Voucher;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminVoucherRequestTransformer;
use App\Modules\CardUser\Http\Requests\CreateVoucherRequestValidation;
use App\Modules\CardUser\Transformers\CardUserVoucherRequestTransformer;
use App\Modules\CardUser\Notifications\VoucherRequested;
use App\Modules\Admin\Notifications\MerchantCreditApproved;

class VoucherRequest extends Model
{
	use SoftDeletes;

	protected $fillable = ['code', 'amount'];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function voucher()
	{
		return $this->belongsTo(Voucher::class);
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models', 'middleware' => ['verified_card_users']], function () {
			Route::get('voucher-request', 'VoucherRequest@getVoucherRequest')->middleware('auth:card_user');
			Route::post('voucher-request/create', 'VoucherRequest@makeVoucherRequest')->middleware('auth:card_user');
		});
	}

	static function accountantRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models', 'prefix' => 'api'], function () {
			Route::put('voucher-request/{voucher_request}/approve', 'VoucherRequest@approveVoucherRequest')->middleware('auth:normal_admin');
			Route::put('voucher-request/{voucher_request}/allocate', 'VoucherRequest@allocateVoucherRequest')->middleware('auth:normal_admin');
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			Route::get('voucher-requests', 'VoucherRequest@getAllVoucherRequests')->middleware('auth:admin,normal_admin');
		});
	}

	/**
	 * ! Card User routes
	 */
	public function getVoucherRequest()
	{
		try {
			return (new CardUserVoucherRequestTransformer)->transform(auth()->user()->pending_voucher_request);
		} catch (\Throwable $th) {
			return response()->json(['message' => 'User has no existing voucher request'], 404);
		}
	}

	public function makeVoucherRequest(CreateVoucherRequestValidation $request)
	{
		$voucher_request = auth()->user()->voucher_request()->create([
			'amount' => auth()->user()->merchant_limit
		]);

		ActivityLog::logUserActivity(auth()->user()->email . ' requested for a merchant credit');

		auth()->user()->notify(new VoucherRequested);

		return response()->json((new CardUserVoucherRequestTransformer)->transform($voucher_request), 201);
	}

	/**
	 * ! Admin routes
	 */
	public function getAllVoucherRequests()
	{
		return (new AdminVoucherRequestTransformer)->collectionTransformer(self::all(), 'transformForAdminViewVoucherRequests');
	}

	public function approveVoucherRequest(VoucherRequest $voucher_request)
	{
		if (!intval($voucher_request->voucher_id)) {
			return generate_422_error(['voucher' => ['No voucher assigned to this request yet']]);
		} else {

			$card_user = $voucher_request->card_user;

			$voucher_request->approved_at = now();
			$voucher_request->approved_by = auth()->id();
			$voucher_request->save();

			ActivityLog::logAdminActivity(auth()->user()->email . ' approved ' . to_naira($voucher_request->amount) . ' merchant credit for ' . $card_user->email);

			$card_user->notify(new MerchantCreditApproved);

			return response()->json(['rsp' => []], 204);
		}
	}

	public function allocateVoucherRequest(VoucherRequest $voucher_request)
	{

		/** check that there is no voucher associated previously to request */
		if (intval($voucher_request->voucher_id)) {
			return generate_422_error(['voucher' => ['This voucher request already has a voucher allocated to it']]);
		}

		/** Check voucher exists */
		if (!Voucher::exists(request('voucher_code'))) {
			return generate_422_error(['voucher' => ['This Voucher does not exist in the records']]);
		}

		$voucher = Voucher::where('code', request('voucher_code'))->first();

		/** Check voucher has not being assigned to another user */
		if ($voucher->card_user_id) {
			return generate_422_error(['voucher' => ['This Voucher belongs to another user']]);
		}

		/** Check that the voucher amount matches the request amount */
		if (intval($voucher_request->amount) !== intval($voucher->amount)) {
			return generate_422_error(['voucher' => ['This Voucher amount does not match the requested voucher amount']]);
		}

		DB::beginTransaction();

		/**  Attach debit voucher id to this request */
		$voucher_request->voucher_id = $voucher->id;
		$voucher_request->save();

		/** Allocate the voucher to the user that made the request */
		$voucher->card_user_id = $voucher_request->card_user_id;
		$voucher->save();

		/** Create activity */
		ActivityLog::logAdminActivity(auth()->user()->email . ' allocated voucher ' . $voucher->code . ' to request: ' . $voucher_request->id);

		DB::commit();
		return response()->json([], 204);
	}
}
