<?php

namespace App\Modules\Admin\Models;

use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\Model;

class CardUserCategory extends Model
{
	protected $fillable = [
		'category_name',
		'credit_limit',
	];

	// protected $table = "card_users";

	public function card_users()
	{
		return $this->hasMany(CardUser::class);
	}
}
