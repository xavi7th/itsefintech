<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Modules\CardUser\Models\OTP
 *
 * @property int $id
 * @property int $card_user_id
 * @property int $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\OTP newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\OTP newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\OTP query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\OTP whereCardUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\OTP whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\OTP whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\OTP whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\OTP whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OTP extends Model
{
	protected $fillable = ['code'];
	protected $table = 'otps';
	protected $casts = [
		'code' => 'int'
	];
}
