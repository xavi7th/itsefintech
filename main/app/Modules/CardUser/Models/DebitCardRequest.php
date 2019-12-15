<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCardRequestStatus;
use App\Modules\Admin\Transformers\AdminDebitCardRequestTransformer;

class DebitCardRequest extends Model
{
	protected $fillable = [
		'address', 'phone', 'debit_card_request_status_id', 'payment_method', 'zip', 'city'
	];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function debit_card_request_status()
	{
		return $this->belongsTo(DebitCardRequestStatus::class);
	}

	static function routes()
	{
		Route::get('debit-card-requests', function () {
			return (new AdminDebitCardRequestTransformer)->collectionTransformer(DebitCardRequest::all(), 'transformForAdminViewDebitCardRequests');
		})->middleware('auth:admin');

		// Route::post('{user}/debit-card-request/create', function (DebitCardRequestCreationValidation $request, CardUser $user) {
		// 	try {
		// 		DB::beginTransaction();
		// 		$debit_card_request = $user->debit_card_requests()->create($request->all());
		// 		DB::commit();
		// 		return response()->json(['rsp' => $debit_card_request], 201);
		// 	} catch (\Throwable $e) {
		// 		if (app()->environment() == 'local') {
		// 			return response()->json(['error' => $e->getMessage()], 500);
		// 		}
		// 		return response()->json(['rsp' => 'error occurred'], 500);
		// 	}
		// })->middleware('auth:admin');

		Route::put('debit-card-request/{debit_card_request}/allocate', function (DebitCardRequest $debit_card_request) {

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

			/** Check card has not being assigned to another user */
			$debit_card = DebitCard::retrieve(request('card_number'));
			if ($debit_card->card_user_id) {
				return generate_422_error(['card' => ['This Debit Card belongs to another user']]);
			}

			DB::beginTransaction();

			/**  Attach debit card id to this request */
			$debit_card_request->debit_card_id = $debit_card->id;

			/** Set last_updated_by of this request */
			$debit_card_request->last_updated_by = auth()->id();
			$debit_card_request->save();

			/** Allocate the card to the user that made the request */
			$debit_card->card_user_id = $debit_card_request->card_user_id;
			$debit_card->save();

			/** Create activity */
			auth()->user()->log('Attached debit card ' . $debit_card->card_number . ' to request: ' . $debit_card_request->id);

			DB::commit();
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::put('debit-card-request/{debit_card_request}/paid', function (DebitCardRequest $debit_card_request) {
			$debit_card_request->is_paid = true;
			$debit_card_request->save();
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::put('debit-card-request/{debit_card_request}/status/update', function (DebitCardRequest $debit_card_request) {
			// return request('details.debit_card_request_status_id');
			if (is_null($debit_card_request->debit_card_id)) {
				return generate_422_error(['invalid' => ['No debit card has been assigned to this debit card request']]);
			}
			$debit_card_request->debit_card_request_status_id = request('details.debit_card_request_status_id');
			$debit_card_request->save();
			return response()->json(['new_status' => DebitCardRequestStatus::find(request('details.debit_card_request_status_id'))->name], 203);
		})->middleware('auth:admin');

		Route::delete('debit-card-request/{debit_card_request}/delete', function (DebitCardRequest $debit_card_request) {
			return;
			$debit_card_request->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
