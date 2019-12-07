<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
	protected $fillable = ['code'];
	protected $table = 'otps';
	protected $casts = [
		'code' => 'int'
	];
}
