<?php

namespace App\Modules\CardUser\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\CardUser\Models\CardUser;
use App\Modules\CardUser\Models\LoanRequest;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanTransaction extends Model
{
	use SoftDeletes;

	protected $fillable = ['card_user_id', 'amount', 'transaction_type', 'next_installment_due_date',];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'next_installment_due_date',
	];

	public function loan_request()
	{
		return $this->belongsTo(LoanRequest::class);
	}

	public function card_user()
	{
		return $this->belongsTo(CardUser::class);
	}
}
