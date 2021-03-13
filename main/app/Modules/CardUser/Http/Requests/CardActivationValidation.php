<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Http\Request;
use App\Modules\CardUser\Models\CardUser;
use App\Traits\AuthorizesAfterValidation;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|CardUser user()
 */
class CardActivationValidation extends FormRequest
{
  use AuthorizesAfterValidation;

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'card_id' => 'required|integer|exists:debit_cards,id',
      'csc' => 'required|numeric',
      'bvn' => 'required|numeric|digits_between:11,16',
    ];
  }

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorizeValidated()
  {
    print_r(DebitCard::find($this->card_id)->toArray());
    exit;
    return true;
    return DebitCard::find($this->card_id)->belongs_to_user($this->user());
  }



  /**
   * Configure the error messages for the defined validation rules.
   *
   * @return array
   */
  public function messages()
  {
    return [
      'card_id.required' => 'Select a debit card to activate',
      'card_id.exists' => 'Invalid debit card provided',
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
