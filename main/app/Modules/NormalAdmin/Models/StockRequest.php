<?php

namespace App\Modules\NormalAdmin\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\NormalAdmin\Transformers\AdminStockRequestTransformer;

/**
 * App\Modules\NormalAdmin\Models\StockRequest
 *
 * @property int $id
 * @property int $sales_rep_id
 * @property int $number_of_cards
 * @property int $is_processed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Modules\SalesRep\Models\SalesRep $sales_rep
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\NormalAdmin\Models\StockRequest onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest whereIsProcessed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest whereNumberOfCards($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest whereSalesRepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\NormalAdmin\Models\StockRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\NormalAdmin\Models\StockRequest withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\NormalAdmin\Models\StockRequest withoutTrashed()
 * @mixin \Eloquent
 */
class StockRequest extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'sales_rep_id',
		'number_of_cards'
	];

	public function sales_rep()
	{
		return $this->belongsTo(SalesRep::class);
	}


	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\NormalAdmin\Models'], function () {

			Route::get('stock-requests', 'StockRequest@adminViewStockRequests')->middleware('auth:admin,normal_admin');

			Route::put('stock-request/{stock_request}/processed', 'StockRequest@markStockRequestAsProcessed')->middleware('auth:normal_admin');

			Route::delete('stock-request/{stock_request}/delete', 'StockRequest@deleteStockRequest')->middleware('auth:admin');
		});
	}

	static function salesRepRoutes()
	{
		Route::group(['namespace' => '\App\Modules\NormalAdmin\Models', 'prefix' => 'api'], function () {
			Route::post('stock-request/create', 'StockRequest@createStockRequest')->middleware('auth:sales_rep');
		});
	}

	/**
	 * ! Sales Rep Route Methods
	 */
	public function createStockRequest()
	{
		if (!request('num')) {
			return generate_422_error(['err' => ['Specify number of cards']]);
		}
		/** Make a stock request for the logged in sales rep */
		StockRequest::updateOrCreate(
			[
				'sales_rep_id' => auth()->id(),
				'is_processed' => false
			],
			[
				'sales_rep_id' => auth()->id(),
				'number_of_cards' => request('num')
			]
		);

		/** record activity */
		ActivityLog::logAdminActivity('Sales rep ' . auth()->user()->first_name . ' requested for ' . request('num') . ' cards');

		return response()->json([], 204);
	}

	/**
	 * ! Admin Route Methods
	 */
	public function adminViewStockRequests()
	{
		if (auth('admin')->check()) {
			return (new AdminStockRequestTransformer)->collectionTransformer(StockRequest::withTrashed()->get(), 'transformForAdminViewStockRequests');
		} else if (auth('normal_admin')->check()) {
			return (new AdminStockRequestTransformer)->collectionTransformer(StockRequest::all(), 'transformForAdminViewStockRequests');
		}
	}

	public function markStockRequestAsProcessed(StockRequest $stock_request)
	{
		$stock_request->is_processed = true;
		$stock_request->save();

		ActivityLog::logAdminActivity(auth()->user()->email . ' marked ' . $stock_request->sales_rep->email . '\'s stock request as processed');

		return response()->json([], 204);
	}

	public function deleteStockRequest(StockRequest $stock_request)
	{
		return;
		$stock_request->delete();

		ActivityLog::logAdminActivity(auth()->user()->email . ' deleted ' . $stock_request->sales_rep->email . '\'s stock request.');

		return response()->json(['rsp' => true], 204);
	}
}
