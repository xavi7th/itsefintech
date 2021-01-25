<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $fillable = ['phone', 'email', 'subject'];
}
