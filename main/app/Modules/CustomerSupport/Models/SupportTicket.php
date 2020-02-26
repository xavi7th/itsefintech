<?php

namespace App\Modules\CustomerSupport\Models;

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\Department;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\CustomerSupport\Transformers\SupportTicketTransformer;
use App\Modules\CustomerSupport\Http\Requests\CreateSupportTicketValidation;

class SupportTicket extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'channel', 'department_id', 'description', 'ticket_type'
	];


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
			Route::post('support-ticket/create', 'SupportTicket@createSupportTicket')->middleware('auth:customer_support');
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CustomerSupport\Models'], function () {
			Route::get('support-tickets', 'SupportTicket@getSupportTickets')->middleware('auth:admin,sales_rep,card_admin,normal_admin,customer_support');
		});
	}


	/**
	 * ! Admin route methods
	 */
	public function getSupportTickets()
	{
		return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::withTrashed()->get(), 'transformForCustomerSupport'), 200);
	}


	/**
	 * ! Customer Support methods
	 */
	public function createSupportTicket(CreateSupportTicketValidation $request)
	{
		$ticket = auth()->user()->support_tickets()->create($request->all());
		return response()->json((new SupportTicketTransformer)->transformForCustomerSupport($ticket), 201);
	}
}
