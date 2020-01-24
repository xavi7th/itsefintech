<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\DebitCard;

class DebitCardTransaction extends Model
{
	protected $fillable = [
		'card_user_id',
		'amount',
		'trans_description',
		'trans_category',
		'trans_type',
	];

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}

	public function debit_card()
	{
		return $this->belongsTo(DebitCard::class);
	}

	static function cardUserRoutes()
	{
		Route::group(['namespace' => '\App\Modules\CardUser\Models'], function () {
			Route::get('debit-card-transaction/create', 'DebitCardTransaction@getCardUserProfileDetails');
		});
	}
}
