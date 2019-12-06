<?php

namespace App\Modules\Admin\Models;

use App\User;
use App\Modules\Admin\Models\ApiRoute;
use Illuminate\Database\Eloquent\Builder;

class Admin extends User
{
	protected $fillable = [
		'role_id', 'full_name', 'email', 'password', 'phone', 'bvn', 'user_passport', 'gender', 'address', 'dob',
	];
	protected $table = "admins";
	protected $dates = ['dob'];
	const DASHBOARD_ROUTE_PREFIX = 'admin-panel';

	static function canAccess()
	{
		return parent::isAdmin();
	}

	public function is_verified()
	{
		return $this->verified_at !== null;
	}

	/**
	 * Check if the currently authenticated user is an admin
	 *
	 * @return void
	 */
	protected static function isAdmin()
	{
		return Auth::admin() ? true : false;
	}

	public function permitted_api_routes()
	{
		return $this->belongsToMany(ApiRoute::class, 'api_route_permissions', 'user_id')->withTimestamps();
	}

	// /**
	//  * The booting method of the model
	//  *
	//  * @return void
	//  */
	// protected static function boot()
	// {
	// 	parent::boot();

	// 	static::addGlobalScope('adminsOnly', function (Builder $builder) {
	// 		$builder->where('role_id', parent::$admin_id);
	// 	});
	// }
}
