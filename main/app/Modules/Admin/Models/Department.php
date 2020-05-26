<?php

namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\CustomerSupport\Models\SupportTicket;

/**
 * App\Modules\Admin\Models\Department
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CustomerSupport\Models\SupportTicket[] $support_tickets
 * @property-read int|null $support_tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Department query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Department whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\Department whereName($value)
 * @mixin \Eloquent
 */
class Department extends Model
{
	protected $fillable = [];

	public function support_tickets()
	{
		return $this->hasMany(SupportTicket::class);
	}

	static function salesRepsId()
	{
		return self::where('name', 'Sales Rep')->first()->id;
	}

	static function accountantsId()
	{
		return self::where('name', 'Accountant')->first()->id;
	}

	static function accountOfficersId()
	{
		return self::where('name', 'Account Officer')->first()->id;
	}

	static function adminId()
	{
		return self::where('name', 'Admin')->first()->id;
	}

	static function normalAdminId()
	{
		return self::where('name', 'Normal Admin')->first()->id;
	}

	static function cardAdminId()
	{
		return self::where('name', 'Card Admin')->first()->id;
	}

	static function customerSupportId()
	{
		return self::where('name', 'Customer Support')->first()->id;
	}
}
