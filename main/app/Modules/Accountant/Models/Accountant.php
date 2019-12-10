<?php

namespace App\Modules\Accountant\Models;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Admin\Models\ApiRoute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accountant extends User
{
	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'accountant';

	static function canAccess()
	{
		return Auth::guard('accountant')->check();
	}

	public function is_verified()
	{
		return $this->verified_at !== null;
	}

	public function api_routes()
	{
		return $this->morphToMany(ApiRoute::class, 'user', 'api_routes_permissions', 'user_id');
	}

	protected static function boot()
	{
		parent::boot();
		static::deleting(function (Accountant $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}
}
