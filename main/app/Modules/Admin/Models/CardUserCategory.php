<?php

namespace App\Modules\Admin\Models;

use App\Modules\CardUser\Models\CardUser;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Modules\Admin\Models\CardUserCategory
 *
 * @property int $id
 * @property string $category_name
 * @property float $credit_limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $last_updated_by
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\CardUser\Models\CardUser[] $card_users
 * @property-read int|null $card_users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\CardUserCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\CardUserCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\CardUserCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\CardUserCategory whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\CardUserCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\CardUserCategory whereCreditLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\CardUserCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\CardUserCategory whereLastUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Modules\Admin\Models\CardUserCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

	public function is_student()
	{
		return $this->category_name == 'Undergraduate';
	}
}
