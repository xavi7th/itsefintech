<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\BasicSite\Exceptions\AxiosValidationExceptionBuilder;

class EditUserProfileValidation extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email' => ['filled', 'email', Rule::unique('users')->ignore(Auth::carduser()->id)],
			'name' => 'filled|string',
			'password' => 'nullable|min:6|regex:/^([0-9a-zA-Z-\.\@]+)$/'
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
	// public function messages()
	// {
	// 	return [
	// 		'primary_contact_name.required' => 'The name of the primary contact is required',
	// 		'primary_contact_name.alpha_dash' => 'The name of the primary contact can only contain alphabets, numbers underscores and dashes',
	// 		'primary_contact_number.required' => 'The phone number of the primary contact is required',
	// 		'primary_contact_email.required' => 'The email provided for the primary contact is invalid',
	// 		'accommodation_name.required' => 'The name of the accommodation is required',
	// 		'price.required' => 'The price of the accommodation is required',
	// 		'price.numeric' => 'Invalid price',
	// 		'school_id.exists' => 'Sorry we do not have an ambassador program in that school yet',
	// 		'school_id.required' => 'You must tell us the school where this accommodation is',
	// 		'space_type_id.required' => 'Space type is required',
	// 		'space_type_id.exists' => 'Selected space type is invalid',
	// 		'number_of_spaces_available.required' => 'Number of spaces available required',
	// 	];
	// }

	/**
	 * Overwrite the validator response so we can customise it per the structure requested from the fronend
	 *
	 * @param \Illuminate\Contracts\Validation\Validator $validator
	 * @return void
	 */
	protected function failedValidation(Validator $validator)
	{
		/**
		 * * Alternatively throw new AuthenticationFailedException($validator). Remember to use App\Exceptions\Admin\AuthenticationFailedException;
		 * * And handle there. That will help for reuse. Handling here for compactness purposes
		 * ? Who knows they might ask for a different format for the enxt validation
		 */
		throw new AxiosValidationExceptionBuilder($validator);
	}
}
