<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\DebitCardRequest;

class DebitCardRequestStatus extends Model
{
	protected $fillable = [];

	public function debit_card_requests()
	{
		return $this->hasMany(DebitCardRequest::class);
	}
}
