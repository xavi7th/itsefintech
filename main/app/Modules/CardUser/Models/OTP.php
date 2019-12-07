<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
	protected $fillable = ['otp'];
	protected $table = 'otps';
}
