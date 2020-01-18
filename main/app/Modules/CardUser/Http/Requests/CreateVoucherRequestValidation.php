<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;


class CreateVoucherRequestValidation extends FormRequest
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
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return auth('card_user')->check();
	}



	/**
	 * Configure the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'email.exists' => 'Invalid details provided',
			// 'primary_contact_name.alpha_dash' => 'The name of the primary contact can only contain alphabets, numbers underscores and dashes',
			// 'primary_contact_number.required' => 'The phone number of the primary contact is required',
			// 'primary_contact_email.required' => 'The email provided for the primary contact is invalid',
			// 'accommodation_name.required' => 'The name of the accommodation is required',
			// 'price.required' => 'The price of the accommodation is required',
			// 'space_type_id.exists' => 'Selected space type is invalid',
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
			if ($this->user()->has_pending_voucher_request()) {
				$validator->errors()->add('voucher_request', 'You already have a pending voucher request');
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
		throw new AuthorizationException('You are not authorised to make voucher requests');
	}
}
