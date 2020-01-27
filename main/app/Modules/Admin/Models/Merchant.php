<?php

namespace App\Modules\Admin\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\Admin\Models\MerchantCategory;
use App\Modules\Admin\Models\MerchantTransaction;
use Illuminate\Foundation\Auth\Access\Authorizable;
use App\Modules\Admin\Transformers\AdminMerchantTransformer;
use App\Modules\Admin\Http\Requests\CreateMerchantValidation;
use App\Modules\CardUser\Transformers\CardIUserMerchantTransformer;
use App\Modules\Admin\Transformers\AdminMerchantCategoryTransformer;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Merchant  extends Model implements AuthenticatableContract, AuthorizableContract
{
	use Authenticatable, Authorizable;

	protected $fillable = [
		'name', 'unique_code', 'email', 'phone', 'password', 'merchant_category_id', 'address'
	];

	const DASHBOARD_ROUTE_PREFIX = 'merchant-area';


	public function activities()
	{
		return $this->morphMany(ActivityLog::class, 'user');
	}

	public function merchant_transactions()
	{
		return $this->hasMany(MerchantTransaction::class);
	}

	public function merchant_category()
	{
		return $this->belongsTo(MerchantCategory::class);
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			Route::get('merchant-list', 'Merchant@viewAllMerchants');
		});
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
	 * ! Card User Routes
	 */

	public function viewAllMerchants()
	{
		return (new CardIUserMerchantTransformer)->collectionTransformer(self::all(), 'transform');
	}

	/**
	 * ! Admin routes
	 */
	public function getAllMerchants()
	{
		return collect((new AdminMerchantTransformer)->collectionTransformer(self::all(), 'transformForAdminViewMerchants'))
			->merge((new AdminMerchantCategoryTransformer)->collectionTransformer(MerchantCategory::all(), 'transformForAdminViewMerchantCategories'));
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
		$merchant->is_active = true;
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
