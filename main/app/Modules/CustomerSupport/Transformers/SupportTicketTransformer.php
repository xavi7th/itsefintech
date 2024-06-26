<?php

namespace App\Modules\CustomerSupport\Transformers;

use Illuminate\Support\Str;
use App\Modules\Admin\Models\Department;
use App\Modules\CustomerSupport\Models\SupportTicket;

class SupportTicketTransformer
{
	public function collectionTransformer($collection, $transformerMethod)
	{
		try {
			return [
				'total' => $collection->count(),
				'current_page' => $collection->currentPage(),
				'path' => $collection->resolveCurrentPath(),
				'to' => $collection->lastItem(),
				'from' => $collection->firstItem(),
				'last_page' => $collection->lastPage(),
				'next_page_url' => $collection->nextPageUrl(),
				'per_page' => $collection->perPage(),
				'prev_page_url' => $collection->previousPageUrl(),
				'total' => $collection->total(),
				'first_page_url' => $collection->url($collection->firstItem()),
				'last_page_url' => $collection->url($collection->lastPage()),
				'support_tickets' => $collection->map(function ($v) use ($transformerMethod) {
					return $this->$transformerMethod($v);
				})
			];
		} catch (\Throwable $e) {
			return [
				'support_tickets' => $collection->map(function ($v) use ($transformerMethod) {
					return $this->$transformerMethod($v);
				}),
				'departments' => Department::get(['display_name', 'id'])
			];
		}
	}

	public function basicTransform(SupportTicket $support_ticket)
	{
		return [
			'id' => $support_ticket->id,
			'support_ticket_type' => (string)$support_ticket->support_ticket_type->card_type_name,
			'card_number' => $support_ticket->card_number,
			'year' => $support_ticket->year,
			'cardholder' => auth()->user()->first_name,
		];
	}

	public function transformForCustomerSupport(SupportTicket $support_ticket)
	{
		$status = 'Not Started';
		switch (true) {
			case is_null($support_ticket->assigned_at):
				$status = 'Not Started';
				break;
			case !is_null($support_ticket->assigned_at) && is_null($support_ticket->resolved_at):
				$status = 'In progress';
				break;
			case !is_null($support_ticket->resolved_at) && is_null($support_ticket->deleted_at):
				$status = 'Resolved';
				break;
			case !is_null($support_ticket->deleted_at):
				$status = 'Closed';
				break;
		}
		return [
			'id' => (int)$support_ticket->id,
			'created_by' => (string)$support_ticket->customer_support->full_name,
			'created_at' => (string)$support_ticket->created_at,
			'ticket_type' => (string)$support_ticket->ticket_type,
			'channel' => (string)$support_ticket->channel,
			'description' => (string)$support_ticket->description,
			'department' => (string)$support_ticket->department->display_name,
			'department_slug' => (string)Str::snake($support_ticket->department->name),
			'is_started' => (bool)$support_ticket->assigned_at,
			'started_at' => (string)$support_ticket->assigned_at,
			'started_by' => (string)optional($support_ticket->assignee)->full_name,
			'is_resolved' => (bool)$support_ticket->resolved_at,
			'resolved_at' => (string)$support_ticket->resolved_at,
			'resolved_by' => (string)optional($support_ticket->resolver)->full_name,
			'status' => (string)$status,
			'is_closed' => (bool)$support_ticket->deleted_at,
			'closed_at' => (string)$support_ticket->deleted_at,
		];
	}
}
