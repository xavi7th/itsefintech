<?php

namespace App\Modules\CardUser\Http\Requests;

use App\Modules\CardUser\Models\CardUser;
use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser user()
 */
class ResolveBankAccountNameValidation extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'account_number' => 'required|numeric|digits:10',
			'sort_code' => 'required|string|max:10',
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
			'account_number' => [
        'required' => 'Enter an account number to validate',
        'numeric' => 'The account number to validate must be a number',
        'digits' => 'NUBAN account numbers must be 10 digits'
      ],
			'sort_code' => [
        'required' => 'A bank must be selected',
        'max' => 'Invalid bank selected'
      ],
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
