<?php

namespace App\Modules\CardUser\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminDebitCardTransformer;
use App\Modules\Admin\Http\Requests\DebitCardCreationValidation;

class DebitCard extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'card_number', 'month', 'year', 'csc', 'sales_rep_id', 'card_user_id'
	];
	protected $appends = ['exp_date'];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function sales_rep()
	{
		return $this->belongsTo(SalesRep::class);
	}

	public function getExpDateAttribute()
	{
		return Carbon::createFromDate($this->year, $this->month + 1, 1);
	}

	public function getCardNumberAttribute($value)
	{
		return 'ending in ' . substr(decrypt($value), -4);
	}
	public function setCardNumberAttribute($value)
	{
		$this->attributes['card_number'] = encrypt($value);
	}

	static function routes()
	{
		Route::get('debit-cards', function () {
			return (new AdminDebitCardTransformer)->collectionTransformer(DebitCard::withTrashed()->get(), 'transformForAdminViewDebitCards');
		})->middleware('auth:admin');

		Route::post('debit-card/create', function (DebitCardCreationValidation $request, CardUser $user) {
			try {
				DB::beginTransaction();
				$debit_card = $user->debit_cards()->create($request->all());

				auth()->user()->log('Created new Debit card ' . $debit_card->card_number);

				DB::commit();
				return response()->json(['rsp' => $debit_card], 201);
			} catch (\Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::put('debit-card/{debit_card}/suspension', function (DebitCard $debit_card) {
			$debit_card->is_suspended = !$debit_card->is_suspended;
			$debit_card->save();
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::put('debit-card/{debit_card}/activate', function (DebitCard $debit_card) {
			$debit_card->is_admin_activated = true;
			$debit_card->save();
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::put('debit-card/{debit_card}/assign', function (DebitCard $debit_card) {
			if (!request('email')) {
				return response()->json([
					'error' => 'form validation error',
					'message' => [
						'email' => ['Email field is required']
					]
				], 422);
			}
			$sales_rep = SalesRep::where('email', request('email'))->firstOrFail();
			$debit_card->update([
				'sales_rep_id' => $sales_rep->id
			]);
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::delete('debit-card/{debit_card}/delete', function (DebitCard $debit_card) {
			return;
			$debit_card->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
