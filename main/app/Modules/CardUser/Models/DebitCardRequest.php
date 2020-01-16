<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Models\DebitCardType;
use App\Modules\CardUser\Models\DebitCardRequestStatus;
use App\Modules\Admin\Transformers\AdminDebitCardRequestTransformer;

class DebitCardRequest extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'address', 'phone', 'debit_card_request_status_id', 'payment_method', 'zip', 'city', 'debit_card_type_id'
	];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function debit_card_request_status()
	{
		return $this->belongsTo(DebitCardRequestStatus::class);
	}

	public function debit_card()
	{
		return $this->belongsTo(DebitCard::class);
	}

	public function debit_card_type()
	{
		return $this->belongsTo(DebitCardType::class);
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {

			Route::get('debit-card-requests', 'DebitCardRequest@getDebitCardRequests')->middleware('auth:admin');

			Route::put('debit-card-request/{debit_card_request}/allocate', 'DebitCardRequest@allocateCardToRequest')->middleware('auth:admin');

			Route::put('debit-card-request/{debit_card_request}/paid', 'DebitCardRequest@markRequestAsPaid')->middleware('auth:admin');

			Route::put('debit-card-request/{debit_card_request}/paid/confirm', 'DebitCardRequest@confirmRequestPayment')->middleware('auth:admin');

			Route::put('debit-card-request/{debit_card_request}/status/update', 'DebitCardRequest@updateRequestStatus')->middleware('auth:admin');

			Route::delete('debit-card-request/{debit_card_request}/delete', 'DebitCardRequest@deleteCardRequest')->middleware('auth:admin');
		});
	}

	/**
	 * ! Admin routes
	 */
	public function getDebitCardRequests()
	{
		return (new AdminDebitCardRequestTransformer)->collectionTransformer(DebitCardRequest::withTrashed()->get(), 'transformForAdminViewDebitCardRequests');
	}

	public function allocateCardToRequest(DebitCardRequest $debit_card_request)
	{

		/** Check that request is a new request */
		if (intval($debit_card_request->debit_card_request_status_id) !== 1) {
			return generate_422_error(['card' => ['This card request is already being processed']]);
		}
		/** check that there is no card associated previously to request */
		if (intval($debit_card_request->debit_card_id)) {
			return generate_422_error(['card' => ['This card request already has a debit card allocated to it']]);
		}

		/** Check card exists */
		if (!DebitCard::exists(request('card_number'))) {
			return generate_422_error(['card' => ['This Debit Card does not exist in the records']]);
		}

		$debit_card = DebitCard::retrieve(request('card_number'));

		/** Check card has being assigned to this sales rep */
		if (auth()->id() !== $debit_card->sales_rep_id) {
			return generate_422_error(['card' => ['This Debit Card has not being assigned to you']]);
		}

		/** Check card has not being assigned to another user */
		if ($debit_card->card_user_id) {
			return generate_422_error(['card' => ['This Debit Card belongs to another user']]);
		}

		/** Check that the debit card matches the request type */
		if ($debit_card_request->debit_card_type_id !== $debit_card->debit_card_type->id) {
			return generate_422_error(['card' => ['This Debit Card type does not match the requested card type']]);
		}

		DB::beginTransaction();

		/**  Attach debit card id to this request */
		$debit_card_request->debit_card_id = $debit_card->id;

		/** Set this sales rep as the one attending to the request */
		$debit_card_request->sales_rep_id = auth()->id();

		/** Set last_updated_by of this request */
		$debit_card_request->last_updated_by = auth()->id();
		$debit_card_request->save();

		/** Allocate the card to the user that made the request */
		$debit_card->card_user_id = $debit_card_request->card_user_id;
		$debit_card->save();

		/** Create activity */
		ActivityLog::logAdminActivity('Attached debit card ' . $debit_card->card_number . ' to request: ' . $debit_card_request->id);

		DB::commit();
		return response()->json([], 204);
	}

	public function markRequestAsPaid(DebitCardRequest $debit_card_request)
	{
		$debit_card_request->is_paid = true;
		$debit_card_request->save();
		return response()->json([], 204);
	}

	public function confirmRequestPayment(DebitCardRequest $debit_card_request)
	{
		$debit_card_request->is_payment_confirmed = true;
		$debit_card_request->confirmed_by = auth()->id();
		$debit_card_request->last_updated_by = auth()->id();
		// $debit_card_request->last_updater_user_type = get_class(auth()->user());
		$debit_card_request->save();
		return response()->json([], 204);
	}

	public function updateRequestStatus(DebitCardRequest $debit_card_request)
	{
		// return request('details.debit_card_request_status_id');
		if (is_null($debit_card_request->debit_card_id)) {
			return generate_422_error(['invalid' => ['No debit card has been assigned to this debit card request']]);
		}
		$debit_card_request->debit_card_request_status_id = request('details.debit_card_request_status_id');
		if (request('details.debit_card_request_status_id') == 7) {
			$debit_card_request->debit_card_request_status_id = now();
		}
		$debit_card_request->save();
		return response()->json(['new_status' => DebitCardRequestStatus::find(request('details.debit_card_request_status_id'))->name], 203);
	}

	public function deleteCardRequest(DebitCardRequest $debit_card_request)
	{
		return;
		$debit_card_request->delete();
		return response()->json(['rsp' => true], 204);
	}
}
