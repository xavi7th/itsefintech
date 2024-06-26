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
			'bvn' => 'numeric|digits_between:11,16',
			'school' => 'string|max:50',
			'department' => 'string|max:50',
			'level' => 'numeric|digits_between:1,10',
			'mat_no' => [
				'string',
				'max:20',
        Rule::unique('card_users')->ignore(auth()->user()),
    	],
			'phone' => [
				'regex:/^[\+]?[0-9\Q()\E\s-]+$/i',
        Rule::unique('card_users')->ignore(auth()->user()),
    	],
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
