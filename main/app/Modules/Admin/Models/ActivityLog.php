<?php

namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\CardAdmin\Models\CardAdmin;
use App\Modules\Accountant\Models\Accountant;

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
		]);
	}

	static function logAdminActivity(string $activity)
	{
		auth()->user()->activities()->create([
			'activity' => $activity
		]);
	}

	static function notifySalesReps(string $activity)
	{
		SalesRep::find(1)->activities()->create([
			'activity' => $activity
		]);
	}

	static function notifyCardAdmins(string $activity)
	{
		CardAdmin::find(1)->activities()->create([
			'activity' => $activity
		]);
	}

	static function notifyAccountants(string $activity)
	{
		Accountant::find(1)->activities()->create([
			'activity' => $activity
		]);
	}

	static function notifyAccountOfficers(string $activity)
	{
		AccountOfficer::find(1)->activities()->create([
			'activity' => $activity
		]);
	}

	static function notifyNormalAdmins(string $activity)
	{
		NormalAdmin::find(1)->activities()->create([
			'activity' => $activity
		]);
	}

	static function notifyAdmins(string $activity)
	{
		Admin::find(1)->activities()->create([
			'activity' => $activity
		]);
	}

	static function notifyCustomerSupports(string $activity)
	{
		CustomerSupport::find(1)->activities()->create([
			'activity' => $activity
		]);
	}
}
