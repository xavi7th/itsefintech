<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\DebitCardRequest;

/**
 * App\Modules\CardUser\Models\DebitCardRequestStatus
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CardUser\Models\DebitCardRequest[] $debit_card_requests
 * @property-read int|null $debit_card_requests_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequestStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequestStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequestStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequestStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\CardUser\Models\DebitCardRequestStatus whereName($value)
 * @mixin \Eloquent
 */
class DebitCardRequestStatus extends Model
{
	protected $fillable = [];

	public function debit_card_requests()
	{
		return $this->hasMany(DebitCardRequest::class);
	}

	static function delivered_id()
	{
		return self::where('name', 'Delivered to customer')->first()->id;
	}
}
