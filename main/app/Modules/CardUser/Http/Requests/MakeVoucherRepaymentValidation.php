<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;


class MakeVoucherRepaymentValidation extends FormRequest
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
			'amount.required' => 'Repayment amount not specified',
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
			 * Check if the repayment amount is up to the required amount
			 */
			$repayment_amount = $this->user()->active_voucher->breakdownStatistics()->current_repayment_amount;

			// dd($repayment_amount);

			if ($repayment_amount <= 0) {
				$validator->errors()->add('loan_amount', 'Merchant loan has been fully paid already');
				return;
			}

			if ($this->amount > $repayment_amount) {
				$validator->errors()->add('loan_amount', 'Amount paid is greater than the merchant loan balance');
			}


			if ($this->amount < $repayment_amount) {
				$validator->errors()->add('loan_amount', 'Amount cannot be less than ' . $repayment_amount);
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
}
