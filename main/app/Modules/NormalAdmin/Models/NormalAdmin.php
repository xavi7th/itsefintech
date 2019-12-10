<?php

namespace App\Modules\NormalAdmin\Models;


use App\User;
use Illuminate\Support\Facades\Auth;
use App\Modules\Admin\Models\ApiRoute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class NormalAdmin extends User
{

	use SoftDeletes;

	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $table = "normal_admins";
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'backend';

	static function canAccess()
	{
		return Auth::guard('normal_admin')->check();
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
		static::deleting(function (Admin $user) {
			if ($user->isForceDeleting()) {
				$user->api_routes()->detach();
			}
		});
	}
}
