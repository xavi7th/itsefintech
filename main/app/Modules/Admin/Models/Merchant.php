<?php

namespace App\Modules\Admin\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\Admin\Transformers\AdminMerchantTransformer;
use App\Modules\Admin\Http\Requests\CreateMerchantValidation;

class Merchant extends Model
{

	protected $fillable = [
		'name', 'unique_code', 'email', 'phone'
	];

	const DASHBOARD_ROUTE_PREFIX = 'merchant-area';


	public function activities()
	{
		return $this->morphMany(ActivityLog::class, 'user');
	}


	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			Route::get('merchants', 'Merchant@getAllMerchants')->middleware('auth:admin,normal_admin');
			Route::post('merchant/create', 'Merchant@createMerchant')->middleware('auth:admin,normal_admin');
			Route::put('merchant/{merchant}/suspend', 'Merchant@suspendMerchant')->middleware('auth:admin,normal_admin');
			Route::put('merchant/{merchant}/restore', 'Merchant@restoreMerchant')->middleware('auth:admin,normal_admin');
		});
	}

	/**
	 * ! Admin routes
	 */
	public function getAllMerchants()
	{
		return (new AdminMerchantTransformer)->collectionTransformer(self::all(), 'transformForAdminViewMerchants');
	}
	public function createMerchant(CreateMerchantValidation $request)
	{

		if ($request->auto_generate) {
			$prefix = strtoupper(Str::slug(substr($request->name, 0, 4))) . '-';
			$merchant_id = unique_random('merchants', 'unique_code', $prefix, 8);
			$merchant = Merchant::create(Arr::add($request->except('unique_code'), 'unique_code', $merchant_id));
		} else {
			$merchant = Merchant::create($request->all());
		}
		return response()->json(['merchant' => $merchant], 201);
	}
	public function suspendMerchant(Merchant $merchant)
	{
		$merchant->is_active = false;
		$merchant->save();
		return response()->json(['merchant' => $merchant], 204);
	}

	public function restoreMerchant(Merchant $merchant)
	{
		$merchant->is_active = true;
		$merchant->save();
		return response()->json(['merchant' => $merchant], 204);
	}
}
