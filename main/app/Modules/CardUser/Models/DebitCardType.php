<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CardUser\Models\DebitCardRequest;
use App\Modules\Admin\Http\Requests\EditDebitCardTypeValidation;
use App\Modules\Admin\Transformers\AdminDebitCardTypeTransformer;
use App\Modules\Admin\Http\Requests\DebitCardTypeCreationValidation;

class DebitCardType extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'card_type_name', 'amount', 'max_amount'
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
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('debit-card-types', 'DebitCardType@getDebitCardTypes')->middleware('auth:admin');

			Route::post('debit-card-type/create', 'DebitCardType@createDebitCardType')->middleware('auth:admin');

			Route::put('debit-card-type/{debit_card_type}', 'DebitCardType@editDebitCardType')->middleware('auth:admin');

			Route::delete('debit-card/{debit_card}/delete', 'DebitCardType@deleteDebitCardType')->middleware('auth:admin');
		});
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models', 'middleware' => ['verified_card_users']], function () {
			Route::get('debit-card-type/{debit_card_type}/price', 'DebitCardType@getPrice')->middleware('auth:card_user');
		});
	}

	/**
	 * ! Card User Routes
	 */
	public function getPrice(DebitCardType $debit_card_type)
	{
		return $debit_card_type;
	}

	/**
	 * ! Admin Routes
	 */
	public function getDebitCardTypes($rep = null)
	{
		$debit_card_types = DebitCardType::all();
		return (new AdminDebitCardTypeTransformer)->collectionTransformer($debit_card_types, 'transformForAdminViewDebitCardTypes');
	}

	public function createDebitCardType(DebitCardTypeCreationValidation $request)
	{

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
	}

	public function editDebitCardType(EditDebitCardTypeValidation $request, DebitCardType $debit_card_type)
	{

		try {
			DB::beginTransaction();

			$debit_card_type->update($request->all());

			ActivityLog::logAdminActivity('Edited Debit card type details: ' . $debit_card_type->card_type_name);

			DB::commit();
			return response()->json(['rsp' => true], 204);
		} catch (\Throwable $e) {
			if (app()->environment() == 'local') {
				return response()->json(['error' => $e->getMessage()], 500);
			}
			return response()->json(['rsp' => 'error occurred'], 500);
		}
	}

	public function deleteDebitCardType(DebitCard $debit_card)
	{
		return;
		$debit_card->delete();
		return response()->json(['rsp' => true], 204);
	}
}
