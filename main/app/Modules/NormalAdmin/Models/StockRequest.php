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


	static function routes()
	{

		Route::post('debit-card/request', function () {
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
			ActivityLog::logAdminActivity('Sales rep ' . auth()->user()->first_name . ' requested for ' . request('num' . ' cards'));

			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::get('stock-requests', function () {
			return (new AdminStockRequestTransformer)->collectionTransformer(StockRequest::withTrashed()->get(), 'transformForAdminViewStockRequests');
		})->middleware('auth:admin');

		Route::put('stock-request/{stock_request}/processed', function (StockRequest $stock_request) {
			$stock_request->is_processed = true;
			$stock_request->save();
			return response()->json([], 204);
		})->middleware('auth:admin');

		Route::delete('stock-request/{stock_request}/delete', function (StockRequest $stock_request) {
			return;
			$stock_request->delete();
			return response()->json(['rsp' => true], 204);
		})->middleware('auth:admin');
	}
}
