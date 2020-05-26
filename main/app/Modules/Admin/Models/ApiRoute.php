<?php

namespace App\Modules\Admin\Models;

use App\Modules\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use App\Modules\NormalAdmin\Models\NormalAdmin;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\AccountOfficer\Models\AccountOfficer;
use App\Modules\CardAdmin\Models\CardAdmin;
use App\Modules\CustomerSupport\Models\CustomerSupport;
use App\Modules\SalesRep\Models\SalesRep;

/**
 * App\Modules\Admin\Models\ApiRoute
 *
 * @property int $id
 * @property string $path
 * @property string $name
 * @property string $meta
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\AccountOfficer\Models\AccountOfficer[] $account_officers
 * @property-read int|null $account_officers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Accountant\Models\Accountant[] $accountants
 * @property-read int|null $accountants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Admin\Models\Admin[] $admins
 * @property-read int|null $admins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CardAdmin\Models\CardAdmin[] $card_admins
 * @property-read int|null $card_admins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CustomerSupport\Models\CustomerSupport[] $customer_supports
 * @property-read int|null $customer_supports_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\NormalAdmin\Models\NormalAdmin[] $normal_admins
 * @property-read int|null $normal_admins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\SalesRep\Models\SalesRep[] $sales_reps
 * @property-read int|null $sales_reps_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\ApiRoute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

	public function card_admins()
	{
		return $this->morphedByMany(CardAdmin::class, 'user', 'api_routes_permissions', 'api_route_id');
	}

	public function customer_supports()
	{
		return $this->morphedByMany(CustomerSupport::class, 'user', 'api_routes_permissions', 'api_route_id');
	}

	public function sales_reps()
	{
		return $this->morphedByMany(SalesRep::class, 'user', 'api_routes_permissions', 'api_route_id');
	}
}
