<?php

namespace App\Modules\CustomerSupport\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\Department;
use App\Modules\Admin\Models\ActivityLog;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\CustomerSupport\Transformers\SupportTicketTransformer;
use App\Modules\CustomerSupport\Http\Requests\CreateSupportTicketValidation;

/**
 * App\Modules\CustomerSupport\Models\SupportTicket
 *
 * @property int $id
 * @property int $customer_support_id
 * @property string $ticket_type
 * @property string $channel
 * @property string $description
 * @property int $department_id
 * @property string|null $assigned_at
 * @property int|null $assignee_id
 * @property string|null $assignee_type
 * @property string|null $resolved_at
 * @property int|null $resolver_id
 * @property string|null $resolver_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $assignee
 * @property-read \App\Modules\CustomerSupport\Models\CustomerSupport $customer_support
 * @property-read \App\Modules\Admin\Models\Department $department
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $resolver
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket accountOfficers()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket accountants()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket admins()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket cardAdmins()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket customerSupports()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket normalAdmins()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CustomerSupport\Models\SupportTicket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket salesReps()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereAssignedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereAssigneeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereAssigneeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereCustomerSupportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereResolvedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereResolverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereResolverType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereTicketType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CustomerSupport\Models\SupportTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CustomerSupport\Models\SupportTicket withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\CustomerSupport\Models\SupportTicket withoutTrashed()
 * @mixin \Eloquent
 */
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

	public function scopeAccountants($query)
	{
		return $query->where('department_id', Department::accountantsId());
	}

	public function scopeAccountOfficers($query)
	{
		return $query->where('department_id', Department::accountOfficersId());
	}

	public function scopeAdmins($query)
	{
		return $query->where('department_id', Department::adminId());
	}

	public function scopeCardAdmins($query)
	{
		return $query->where('department_id', Department::cardAdminId());
	}

	public function scopeCustomerSupports($query)
	{
		return $query->where('department_id', Department::customerSupportId());
	}

	public function scopeNormalAdmins($query)
	{
		return $query->where('department_id', Department::normalAdminId());
	}

	public function scopeSalesReps($query)
	{
		return $query->where('department_id', Department::salesRepsId());
	}

	static function customerSupportRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CustomerSupport\Models', 'prefix' => 'api'], function () {
			Route::post('support-ticket/create', 'SupportTicket@createSupportTicket')->middleware('auth:customer_support');
			Route::put('support-ticket/{support_ticket}/accept', 'SupportTicket@acceptSupportTicket')->middleware('auth:admin,sales_rep,card_admin,normal_admin,customer_support,accountant,account_officer');
			Route::put('support-ticket/{support_ticket}/resolved', 'SupportTicket@markSupportTicketAsResolved')->middleware('auth:admin,sales_rep,card_admin,normal_admin,customer_support,accountant,account_officer');
			Route::put('support-ticket/{support_ticket}/close', 'SupportTicket@closeSupportTicket')->middleware('auth:customer_support');
		});
	}

	static function adminRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CustomerSupport\Models'], function () {
			Route::get('support-tickets', 'SupportTicket@getSupportTickets')->middleware('auth:admin,sales_rep,card_admin,normal_admin,customer_support,accountant,account_officer');
		});
	}


	/**
	 * ! Admin route methods
	 */
	public function getSupportTickets()
	{
		if (auth('admin')->check()) {
			return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::withTrashed()->get(), 'transformForCustomerSupport'), 200);
		} else if (auth('customer_support')->check()) {
			return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::withTrashed()->get(), 'transformForCustomerSupport'), 200);
		} else if (auth('sales_rep')->check()) {
			return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::salesReps()->get(), 'transformForCustomerSupport'), 200);
		} else if (auth('card_admin')->check()) {
			return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::cardAdmins()->get(), 'transformForCustomerSupport'), 200);
		} else if (auth('normal_admin')->check()) {
			return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::normalAdmins()->get(), 'transformForCustomerSupport'), 200);
		} else if (auth('accountant')->check()) {
			return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::accountants()->get(), 'transformForCustomerSupport'), 200);
		} else if (auth('account_officer')->check()) {
			return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::accountOfficers()->get(), 'transformForCustomerSupport'), 200);
		} else if (auth('normal_admin')->check()) {
			return response()->json((new SupportTicketTransformer)->collectionTransformer(SupportTicket::normalAdmins()->get(), 'transformForCustomerSupport'), 200);
		}
	}

	/**
	 * ! Customer Support methods
	 */
	public function createSupportTicket(CreateSupportTicketValidation $request)
	{
		DB::beginTransaction();

		$ticket = auth()->user()->support_tickets()->create($request->all());

		ActivityLog::notifyAdmins(auth()->user()->email . ' created a new ' . $ticket->ticket_type . ' support ticket ' . $ticket->id);
		ActivityLog::notifyCustomerSupports(auth()->user()->email . ' created a new ' . $ticket->ticket_type . ' support ticket ' . $ticket->id);

		if ($ticket->department_id == Department::cardAdminId()) {
			ActivityLog::notifyCardAdmins(auth()->user()->email . ' created a new ' . $ticket->ticket_type . ' support ticket ' . $ticket->id);
		} else if ($ticket->department_id == Department::accountantsId()) {
			ActivityLog::notifyAccountants(auth()->user()->email . ' created a new ' . $ticket->ticket_type . ' support ticket ' . $ticket->id);
		} else if ($ticket->department_id == Department::accountOfficersId()) {
			ActivityLog::notifyAccountOfficers(auth()->user()->email . ' created a new ' . $ticket->ticket_type . ' support ticket ' . $ticket->id);
		} else if ($ticket->department_id == Department::normalAdminId()) {
			ActivityLog::notifyNormalAdmins(auth()->user()->email . ' created a new ' . $ticket->ticket_type . ' support ticket ' . $ticket->id);
		} else if ($ticket->department_id == Department::salesRepsId()) {
			ActivityLog::notifySalesReps(auth()->user()->email . ' created a new ' . $ticket->ticket_type . ' support ticket ' . $ticket->id);
		}

		DB::commit();
		return response()->json((new SupportTicketTransformer)->transformForCustomerSupport($ticket), 201);
	}


	public function acceptSupportTicket(SupportTicket $support_ticket)
	{
		DB::beginTransaction();

		$support_ticket->assigned_at = now();
		$support_ticket->assignee_id = auth()->user()->id;
		$support_ticket->assignee_type = get_class(auth()->user());
		$support_ticket->save();


		ActivityLog::notifyAdmins(auth()->user()->email . ' has started working on ticket ' . $support_ticket->id);
		ActivityLog::notifyCustomerSupports(auth()->user()->email . ' has started working on ticket ' . $support_ticket->id);

		if ($support_ticket->department_id == Department::cardAdminId()) {
			ActivityLog::notifyCardAdmins(auth()->user()->email . ' has started working on ticket ' . $support_ticket->id);
		} else if ($support_ticket->department_id == Department::accountantsId()) {
			ActivityLog::notifyAccountants(auth()->user()->email . ' has started working on ticket ' . $support_ticket->id);
		} else if ($support_ticket->department_id == Department::accountOfficersId()) {
			ActivityLog::notifyAccountOfficers(auth()->user()->email . ' has started working on ticket ' . $support_ticket->id);
		} else if ($support_ticket->department_id == Department::normalAdminId()) {
			ActivityLog::notifyNormalAdmins(auth()->user()->email . ' has started working on ticket ' . $support_ticket->id);
		} else if ($support_ticket->department_id == Department::salesRepsId()) {
			ActivityLog::notifySalesReps(auth()->user()->email . ' has started working on ticket ' . $support_ticket->id);
		}


		DB::commit();
		return response()->json(['rsp' => true], 204);
	}

	public function markSupportTicketAsResolved(SupportTicket $support_ticket)
	{
		DB::beginTransaction();

		$support_ticket->resolved_at = now();
		$support_ticket->resolver_id = auth()->user()->id;
		$support_ticket->resolver_type = get_class(auth()->user());
		$support_ticket->save();


		ActivityLog::notifyAdmins(auth()->user()->email . ' has resolved ticket ' . $support_ticket->id);
		ActivityLog::notifyCustomerSupports(auth()->user()->email . ' has resolved ticket ' . $support_ticket->id);

		if ($support_ticket->department_id == Department::cardAdminId()) {
			ActivityLog::notifyCardAdmins(auth()->user()->email . ' has resolved ticket ' . $support_ticket->id);
		} else if ($support_ticket->department_id == Department::accountantsId()) {
			ActivityLog::notifyAccountants(auth()->user()->email . ' has resolved ticket ' . $support_ticket->id);
		} else if ($support_ticket->department_id == Department::accountOfficersId()) {
			ActivityLog::notifyAccountOfficers(auth()->user()->email . ' has resolved ticket ' . $support_ticket->id);
		} else if ($support_ticket->department_id == Department::normalAdminId()) {
			ActivityLog::notifyNormalAdmins(auth()->user()->email . ' has resolved ticket ' . $support_ticket->id);
		} else if ($support_ticket->department_id == Department::salesRepsId()) {
			ActivityLog::notifySalesReps(auth()->user()->email . ' has resolved ticket ' . $support_ticket->id);
		}

		DB::commit();

		return response()->json(['rsp' => true], 204);
	}

	public function closeSupportTicket(SupportTicket $support_ticket)
	{
		DB::beginTransaction();

		$support_ticket->deleted_at = now();
		$support_ticket->save();

		ActivityLog::notifyAdmins(auth()->user()->email . ' has closed ticket ' . $support_ticket->id);
		ActivityLog::notifyCustomerSupports(auth()->user()->email . ' has closed ticket ' . $support_ticket->id);

		DB::commit();

		return response()->json(['rsp' => true], 204);
	}
}
