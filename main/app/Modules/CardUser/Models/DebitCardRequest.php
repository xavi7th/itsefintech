<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCardRequestStatus;
use App\Modules\Admin\Transformers\AdminDebitCardRequestTransformer;

class DebitCardRequest extends Model
{
	protected $fillable = [];

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

		Route::put('debit-card-request/{debit_card_request}/paid', function (DebitCardRequest $debit_card_request) {
			$debit_card_request->is_paid = true;
			$debit_card_request->save();
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::put('debit-card-request/{debit_card_request}/status/update', function (DebitCardRequest $debit_card_request) {
			// return request('details.debit_card_request_status_id');
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
