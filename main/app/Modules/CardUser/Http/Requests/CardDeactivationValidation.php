<?php

namespace App\Modules\CardUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use App\Modules\CardUser\Exceptions\AxiosValidationExceptionBuilder;
use App\Modules\CardUser\Models\DebitCard;
use App\Traits\AuthorizesAfterValidation;

class CardDeactivationValidation extends FormRequest
{

  use AuthorizesAfterValidation;

  public function rules()
  {
    return [
      'card_id' => 'required|integer|exists:debit_cards,id',
      'deactivation_reason' => 'required|string|max:50',
    ];
  }

  public function authorizeValidated()
  {
    return DebitCard::find($this->card_id)->belongs_to_user($this->user());
  }

  public function messages()
  {
    return [
      'card_id.required' => 'Select a debit card to block',
      'card_id.exists' => 'Invalid debit card provided',
    ];
  }

  protected function failedValidation(Validator $validator)
  {
    throw new AxiosValidationExceptionBuilder($validator);
  }
}
