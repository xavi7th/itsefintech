<?php

namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\CustomerSupport\Models\SupportTicket;

class Department extends Model
{
	protected $fillable = [];

	public function support_tickets()
	{
		return $this->hasMany(SupportTicket::class);
	}
}
