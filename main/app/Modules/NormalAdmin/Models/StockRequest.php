<?php

namespace App\Modules\NormalAdmin\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\ActivityLog;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\NormalAdmin\Transformers\AdminStockRequestTransformer;

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
		Route::group(['namespace' => '\App\Modules\Admin\Http\Controllers'], function () {
			Route::post('debit-card/request', 'StockRequest@createStockRequest')->middleware('auth:admin');

			Route::get('stock-requests', 'StockRequest@adminViewStockRequests')->middleware('auth:admin');

			Route::put('stock-request/{stock_request}/processed', 'StockRequest@markStockRequestAsProcessed')->middleware('auth:admin');

			Route::delete('stock-request/{stock_request}/delete', 'StockRequest@deleteStockRequest')->middleware('auth:admin');
		});
	}

	static function salesRepRoutes()
	{
		Route::group(['namespace' => '\App\Modules\Admin\Http\Controllers'], function () {
			Route::post('debit-card/request', 'StockRequest@createStockRequest')->middleware('auth:admin');
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
		return (new AdminStockRequestTransformer)->collectionTransformer(StockRequest::withTrashed()->get(), 'transformForAdminViewStockRequests');
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
