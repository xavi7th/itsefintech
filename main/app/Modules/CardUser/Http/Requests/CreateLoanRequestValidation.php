<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\CardUser\Models\LoanRequest;
use \Illuminate\Contracts\Validation\Validator;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;


class CreateLoanRequestValidation extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'amount' => 'required|numeric|min:1000',
			'total_duration' => 'required|numeric',
			'repayment_duration' => 'required|numeric|lte:total_duration',
			'repayment_amount' => 'required|numeric',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return $this->user()->due_for_credit();
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
			'amount.min' => 'The loan amount must be a minimum of ₦1,000',
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
			 * Check if user has an unprocessed loan request already
			 */

			// dd($this->amount);

			if ($this->user()->has_loan_request()) {
				$validator->errors()->add('existing_loan_request', 'You have a pending loan request');
				return;
			}

			$requestable_loan_amount = $this->user()->assigned_credit_limit - $this->user()->total_loan_balance();

			if ($this->amount > $requestable_loan_amount) {
				$validator->errors()->add('assigned_credit_limit', 'You can only request a loan of ₦' . number_format($requestable_loan_amount) . ' or lesser');
			}

			/**
			 * Check if the selected repayment duration IN DAYS is less than the minimum allowed
			 */
			if ($this->repayment_duration > config('app.maximum_repayment_duration')) {
				$validator->errors()->add('repayment_duration', 'Maximum repayment duration is ' . config('app.maximum_repayment_duration') . ' days');
				return;
			}

			/**
			 * Check if the selected repayment amount is less than the minimum allowed
			 */
			$min_repayment_amount = LoanRequest::minimumRepaymentAmount($this->amount, $this->total_duration, $this->repayment_duration);

			if ($this->repayment_amount < $min_repayment_amount) {
				$validator->errors()->add('repayment_amount', 'Minimum repayment amount is ₦' . number_format($min_repayment_amount));
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
