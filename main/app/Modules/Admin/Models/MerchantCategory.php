<?php

namespace App\Modules\Admin\Models;

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\Merchant;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Transformers\AdminMerchantCategoryTransformer;

class MerchantCategory  extends Model
{

	protected $fillable = ['name'];

	public function merchants()
	{
		return $this->hasMany(Merchant::class);
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			// Route::get('merchant-list', 'MerchantCategory@viewAllMerchants');
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Models'], function () {
			Route::get('merchant-categories', 'MerchantCategory@getAllMerchantCategories')->middleware('auth:admin,normal_admin');
			Route::post('merchant-category', 'MerchantCategory@createMerchantCategory')->middleware('auth:admin,normal_admin');
			Route::put('merchant-category/{merchant_category}', 'MerchantCategory@editMerchantCategory')->middleware('auth:admin,normal_admin');
		});
	}

	/**
	 * ! Card User Routes
	 */

	// public function viewAllMerchants()
	// {
	// 	return (new CardIUserMerchantTransformer)->collectionTransformer(self::all(), 'transform');
	// }

	/**
	 * ! Admin routes
	 */
	public function getAllMerchantCategories()
	{
		return (new AdminMerchantCategoryTransformer)->collectionTransformer(self::all(), 'transformForAdminViewMerchantCategories');
	}

	public function createMerchantCategory()
	{
		$merchant_category = MerchantCategory::create(request()->all());
		return response()->json(['merchant_category' => $merchant_category], 201);
	}

	public function editMerchantCategory(MerchantCategory $merchant_category)
	{
		$merchant_category->name = request('name');
		$merchant_category->save();
		return response()->json([], 204);
	}
}
