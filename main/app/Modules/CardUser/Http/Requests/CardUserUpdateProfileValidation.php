<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;


class CardUserUpdateProfileValidation extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'first_name' => 'string|max:50',
			'last_name' => 'string|max:50',
			'password' => 'string|min:8',
			'phone' => 'regex:/^[\+]?[0-9\Q()\E\s-]+$/i|unique:card_users,phone',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}



	/**
	 * Configure the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			// 'primary_contact_name.required' => 'The name of the primary contact is required',
			// 'primary_contact_name.alpha_dash' => 'The name of the primary contact can only contain alphabets, numbers underscores and dashes',
			// 'primary_contact_number.required' => 'The phone number of the primary contact is required',
			// 'primary_contact_email.required' => 'The email provided for the primary contact is invalid',
			// 'accommodation_name.required' => 'The name of the accommodation is required',
			// 'price.required' => 'The price of the accommodation is required',
			// 'space_type_id.exists' => 'Selected space type is invalid',
		];
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
}