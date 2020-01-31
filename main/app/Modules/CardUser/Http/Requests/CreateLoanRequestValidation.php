<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\CardUser\Models\LoanRequest;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Auth\Access\AuthorizationException;
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
			// 'repayment_duration' => 'required|numeric|lte:total_duration',
			// 'repayment_amount' => 'required|numeric',
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
			 * FILTER VALIDATE BOOLEAN because the value is sent as string instead of boolean.
			 * Returns true for 'yes', 'on', '1' and 'true'. Returns false otherwise
			 *
			 * If the FILTER_NULL_ON_FAILURE flag is set, it returns null, instead of false, if the
			 * value is not 'false', '0', 'off' or 'no'
			 *
			 * $is_school_fees_loan = filter_var($this->is_school_fees, FILTER_VALIDATE_BOOLEAN);
			 */
			$is_school_fees_loan = filter_var($this->is_school_fees, FILTER_VALIDATE_BOOLEAN);

			/**
			 * Check is a boolean value was set for is_school_fees
			 */
			// if (is_null($is_school_fees_loan)) {
			// 	$validator->errors()->add('school_fees_loan', 'Specify if this is a school fees loan or not');
			// 	return;
			// }


			/**
			 * Check if it's a school fees loan and then check the user's profile
			 */

			if ($is_school_fees_loan) {


				if ($this->user()->has_school_fees_request()) {
					$validator->errors()->add('school_fees_loan', 'You already have a pending school fees request');
					return;
				}

				if (!$this->user()->card_user_category->is_student()) {
					$validator->errors()->add('school_fees_loan', 'School fees loan is only available to students');
					return;
				}

				if (!$this->user()->due_for_school_fees_loan()) {
					$validator->errors()->add('school_fees_loan', 'Incomplete student profile');
					return;
				}

				if ($this->user()->has_active_school_fees_loan()) {
					$validator->errors()->add('school_fees_loan', 'You already have an active unpaid school fees loan');
					return;
				}
			}

			/**
			 * Check if user has an unprocessed loan request already
			 */

			if ($this->isMethod('post') && $this->user()->has_loan_request() && !$is_school_fees_loan) {
				$validator->errors()->add('existing_loan_request', 'You have a pending loan request');
				return;
			}

			$requestable_loan_amount = $this->user()->assigned_credit_limit - $this->user()->total_loan_balance();
			$requestable_loan_amount =  $requestable_loan_amount < 0 ? 0 : $requestable_loan_amount;

			if ($this->isMethod('post') && $this->amount > $requestable_loan_amount && !$is_school_fees_loan) {
				$validator->errors()->add('assigned_credit_limit', 'You currently have an available loan limit of ₦' . number_format($requestable_loan_amount));
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
		throw new AuthorizationException('You are not yet due for loan credit facility');
	}
}
