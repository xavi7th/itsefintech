<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\CardUser\Models\LoanRequest;
use \Illuminate\Contracts\Validation\Validator;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;


class MakeLoanRepaymentValidation extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			// 'loan_request_id' => 'required|exists:loan_requests,id',
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
			'amount.required' => 'Repayment amount not specified',
			'loan_request_id.required' => 'Loan request parameter not specified',
			'loan_request_id.exists' => 'Loan request details not found',
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
			$loan_request = $this->route('loan_request');

			if ($loan_request->loan_balance() <= 0) {
				$validator->errors()->add('loan_amount', 'Loan has been fully paid already');
				return;
			}

			if ($this->amount > $loan_request->loan_balance()) {
				$validator->errors()->add('loan_amount', 'Amount paid is greater than loan balance');
			}


			if ($this->amount < $loan_request->breakdownStatistics()->minimum_repayment_amount) {
				$validator->errors()->add('loan_amount', 'Amount cannot be less than the minimum repayment amount');
			}

			if ($this->amount > $loan_request->breakdownStatistics()->minimum_repayment_amount && ($this->amount < $loan_request->loan_balance() && $this->amount < $loan_request->breakdownStatistics()->scheduled_repayment_amount)) {
				$validator->errors()->add('loan_amount', 'Amount paid is greater than interest amount but less than the loan\'s scheduled repayment amount');
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
