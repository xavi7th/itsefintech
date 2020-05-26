<?php

namespace App\Modules\Admin\Models;

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Models\Merchant;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Transformers\AdminMerchantCategoryTransformer;

/**
 * App\Modules\Admin\Models\MerchantCategory
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Admin\Models\Merchant[] $merchants
 * @property-read int|null $merchants_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\MerchantCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\MerchantCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\MerchantCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\MerchantCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\MerchantCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\MerchantCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\MerchantCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
			Route::get('merchant-categories', 'MerchantCategory@getAllMerchantCategories')->middleware('auth:admin,normal_admin,account_officer');
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

		ActivityLog::notifyAdmins(auth()->user()->email . ' created new merchant category ' . $merchant_category->name);
		ActivityLog::notifyAccountOfficers(auth()->user()->email . ' created new merchant category ' . $merchant_category->name);
		ActivityLog::notifyNormalAdmins(auth()->user()->email . ' created new merchant category ' . $merchant_category->name);

		return response()->json(['merchant_category' => $merchant_category], 201);
	}

	public function editMerchantCategory(MerchantCategory $merchant_category)
	{
		$merchant_category->name = request('name');
		$merchant_category->save();

		ActivityLog::notifyAdmins(auth()->user()->email . ' created merchant category ' . $merchant_category->name . ' details');
		ActivityLog::notifyAccountOfficers(auth()->user()->email . ' created merchant category ' . $merchant_category->name . ' details');
		ActivityLog::notifyNormalAdmins(auth()->user()->email . ' created merchant category ' . $merchant_category->name . ' details');

		return response()->json([], 204);
	}
}
