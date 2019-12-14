<?php

namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
	protected $fillable = [];

	public function user()
	{
		return $this->morphTo();
	}
}
