<?php

namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
	protected $fillable = ['activity'];

	public function user()
	{
		return $this->morphTo();
	}

	static function logUserActivity(string $activity)
	{
		auth()->user()->activities()->create([
			'activity' => $activity
		]);;
	}
	static function logAdminActivity(string $activity)
	{
		auth()->user()->activities()->create([
			'activity' => $activity
		]);;
	}
}
