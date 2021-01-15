<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Http\Request;
use App\Traits\AuthorizesAfterValidation;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;

class CardFundingValidation extends FormRequest
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
      'amount' => 'required|numeric|gt:200',
    ];
  }

  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorizeValidated()
  {
    return DebitCard::find($this->card_id)->belongs_to_user($this->user());
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
