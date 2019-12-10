<?php

namespace App\Modules\Admin\Models;

use App\Modules\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class ApiRoute extends Model
{
	protected $fillable = [
		'path', 'name', 'meta',
	];

	public function admins()
	{
		return $this->morphedByMany(Admin::class, 'user', 'api_routes_permissions', 'api_route_id');
	}
}
