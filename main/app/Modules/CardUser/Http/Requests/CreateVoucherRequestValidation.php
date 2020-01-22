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
			// 'amount' => 'numeric',
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return $this->user()->due_for_merchant_loan();
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

			$requestable_voucher_amount = $this->user()->merchant_limit - $this->user()->merchant_loan_balance();
			$requestable_voucher_amount =  $requestable_voucher_amount < 0 ? 0 : $requestable_voucher_amount;

			if ($this->isMethod('post') && $this->amount > $requestable_voucher_amount) {
				$validator->errors()->add('assigned_merchant_limit', 'You currently have an available merchant limit of ₦' . number_format($requestable_voucher_amount));
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
		throw new AuthorizationException('You are not yet due for merchant credit facility');
	}
}
