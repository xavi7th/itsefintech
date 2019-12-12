<?php

namespace App\Modules\CardUser\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Admin\Transformers\AdminUserTransformer;
use App\Modules\Admin\Http\Requests\DebitCardCreationValidation;

class DebitCard extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'card_number', 'month', 'year', 'csc'
	];
	protected $appends = ['exp_date'];

	public function card_user()
	{
		return $this->hasOne(CardUser::class);
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
			return (new AdminUserTransformer)->collectionTransformer(DebitCard::withTrashed()->get(), 'transformForAdminViewDebitCards');
		})->middleware('auth:admin');

		Route::post('{user}/debit-card/create', function (DebitCardCreationValidation $request, CardUser $user) {
			// dd(encrypt($request->card_number));
			try {
				DB::beginTransaction();
				$debit_card = $user->debit_cards()->create($request->all());
				DB::commit();
				return response()->json(['rsp' => $debit_card], 201);
			} catch (\Throwable $e) {
				if (app()->environment() == 'local') {
					return response()->json(['error' => $e->getMessage()], 500);
				}
				return response()->json(['rsp' => 'error occurred'], 500);
			}
		})->middleware('auth:admin');

		Route::get('debit-card/{debit_card}/permissions', function (DebitCard $debit_card) {
			$permitted_routes = $debit_card->api_routes()->get(['api_routes.id'])->map(function ($item, $key) {
				return $item->id;
			});

			$all_routes = ApiRoute::get(['id', 'description'])->map(function ($item, $key) {
				return ['id' => $item->id, 'description' => $item->description];
			});

			return ['permitted_routes' => $permitted_routes, 'all_routes' => $all_routes];
		})->middleware('auth:admin');

		Route::put('debit-card/{debit_card}/permissions', function (DebitCard $debit_card) {
			$debit_card->api_routes()->sync(request('permitted_routes'));
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('debit-card/{debit_card}/suspend', function (DebitCard $debit_card) {
			if ($debit_card->id === auth()->id()) {
				return response()->json(['rsp' => false], 403);
			}
			$debit_card->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::put('debit-card/{id}/restore', function ($id) {
			DebitCard::withTrashed()->find($id)->restore();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');

		Route::delete('debit-card/{debit_card}/delete', function (DebitCard $debit_card) {
			if ($debit_card->id === auth()->id()) {
				return response()->json(['rsp' => false], 403);
			}
			$debit_card->forceDelete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
