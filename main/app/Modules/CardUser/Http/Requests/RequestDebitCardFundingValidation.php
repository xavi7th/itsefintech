<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\CardUser\Models\LoanRequest;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;
use App\Modules\CardUser\Models\DebitCard;

class RequestDebitCardFundingValidation extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'amount' => 'required|numeric',
			'debit_card_id' => 'required|exists:debit_cards,id',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return $this->user()->first_debit_card()->exists();
	}



	/**
	 * Configure the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'debit_card_id.exists' => 'This Credit Card does not exist in our records',
		];
	}

	/**
	 * Configure the validator instance.
	 *
	 * @param  \Illuminate\Validation\Validator  $validator
	 * @return void
	 */
	public function withValidator($validator)
	{
		$validator->after(function ($validator) {

			/**
			 * Check if the card belongs to the user
			 */
			$debit_card = DebitCard::find($this->debit_card_id);
			if (!$debit_card) {
				return;
			}

			if ($this->user()->id !== optional($debit_card->card_user)->id) {
				$validator->errors()->add('unauthorised', 'This card does not belong to you');
				return;
			}

			if ($this->amount > optional($debit_card->debit_card_type)->max_amount) {
				$validator->errors()->add('unauthorised', 'Maximum card funding amount exceeded');
				return;
			}

			/**
			 * Check if there is a pending funding request for this card
			 */
			if ($debit_card->debit_card_funding_request()->exists()) {
				$validator->errors()->add('unauthorised', 'You already have a pending funding request for this card.');
				return;
			}
		});
	}

	/**
	 * Overwrite the validator response so we can customise it per the structure requested from the fronend
	 *
	 * @param \Illuminate\Contracts\Validation\Validator $validator
	 * @return void
	 */
	protected function failedValidation(Validator $validator)
	{
		/**
		 * * And handle there. That will help for reuse. Handling here for compactness purposes
		 * ? Who knows they might ask for a different format for the enxt validation
		 */
		throw new AxiosValidationExceptionBuilder($validator);
	}

	protected function failedAuthorization()
	{
		throw new AuthorizationException('You are yet to get a credit card. Order one first');
	}
}
