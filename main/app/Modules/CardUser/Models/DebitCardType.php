<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\Admin\Transformers\AdminDebitCardTypeTransformer;
use App\Modules\Admin\Http\Requests\DebitCardTypeCreationValidation;

class DebitCardType extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'card_type_name', 'amount'
	];

	public function debit_cards()
	{
		return $this->hadMany(DebitCard::class);
	}

	public function debit_card_requests()
	{
		return $this->hadMany(DebitCardRequest::class);
	}

	static function adminRoutes()
	{
		Route::get('debit-card-types', function ($rep = null) {
			$debit_card_types = DebitCardType::all();
			return (new AdminDebitCardTypeTransformer)->collectionTransformer($debit_card_types, 'transformForAdminViewDebitCardTypes');
		})->middleware('auth:admin');

		Route::post('debit-card-type/create', function (DebitCardTypeCreationValidation $request) {

			try {
				DB::beginTransaction();

				$debit_card_type = DebitCardType::create($request->all());

				ActivityLog::logAdminActivity('Created new Debit card type ' . $debit_card_type->card_type_name);

				DB::commit();
				return response()->json(['rsp' => $debit_card_type], 201);
			} catch (\Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::put('debit-card-type/{debit_card_type}', function (DebitCardTypeCreationValidation $request, DebitCardType $debit_card_type) {

			try {
				DB::beginTransaction();

				$debit_card_type->update($request->all());

				ActivityLog::logAdminActivity('Created edited Debit card type: ' . $debit_card_type->card_type_name);

				DB::commit();
				return response()->json(['rsp' => true], 204);
			} catch (\Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::delete('debit-card/{debit_card}/delete', function (DebitCard $debit_card) {
			return;
			$debit_card->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
