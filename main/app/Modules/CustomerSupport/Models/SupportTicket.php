<?php

namespace App\Modules\CustomerSupport\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\CustomerSupport\Transformers\SupportTicketTransformer;
use App\Modules\Admin\Models\Department;

class SupportTicket extends Model
{
	use SoftDeletes;

	protected $fillable = [];


	public function assignee()
	{
		return $this->morphTo();
	}

	public function resolver()
	{
		return $this->morphTo();
	}

	public function customer_support()
	{
		return $this->belongsTo(CustomerSupport::class);
	}

	public function department()
	{
		return $this->belongsTo(Department::class);
	}


	static function customerSupportRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CustomerSupport\Models', 'prefix' => 'api'], function () {
			Route::get('support-tickets', 'SupportTicket@getSupportTickets')->middleware('auth:admin,sales_rep,card_admin,normal_admin');
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CustomerSupport\Models'], function () {
			Route::get('support-tickets', 'SupportTicket@getSupportTickets')->middleware('auth:admin,sales_rep,card_admin,normal_admin,customer_support');
		});
	}


	/**
	 * ! Card User route methods
	 */
	public function getSupportTickets()
	{
		return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::withTrashed()->get(), 'transformForCustomerSupport'), 200);
	}
}
