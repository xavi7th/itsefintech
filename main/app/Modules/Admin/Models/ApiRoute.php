<?php

namespace App\Modules\Admin\Models;

use App\Modules\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use App\Modules\NormalAdmin\Models\NormalAdmin;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\AccountOfficer\Models\AccountOfficer;

class ApiRoute extends Model
{
	protected $fillable = [
		'path', 'name', 'meta',
	];

	public function admins()
	{
		return $this->morphedByMany(Admin::class, 'user', 'api_routes_permissions', 'api_route_id');
	}

	public function normal_admins()
	{
		return $this->morphedByMany(NormalAdmin::class, 'user', 'api_routes_permissions', 'api_route_id');
	}

	public function accountants()
	{
		return $this->morphedByMany(Accountant::class, 'user', 'api_routes_permissions', 'api_route_id');
	}
	public function account_officers()
	{
		return $this->morphedByMany(AccountOfficer::class, 'user', 'api_routes_permissions', 'api_route_id');
	}
}
