<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use App\Modules\CardUser\Models\DebitCardRequestStatus;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;


class CardRequestValidation extends FormRequest
{
  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'address' => 'required|string',
      'phone' => 'required|string',
      'state' => 'nullable|string',
      'zip' => 'required|string',
      'payment_method' => 'required|string',
      'city' => 'required|string',
      'debit_card_type_id' => 'required|exists:debit_card_types,id'
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

      /**
       * Check if there is a pending funding request for this card
       */

      if ($this->user()->pending_debit_card_requests()->exists()) {
        $validator->errors()->add('Pending request', 'You already have a pending card request.');
        return;
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
