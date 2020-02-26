<?php

namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\CustomerSupport\Models\SupportTicket;

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
