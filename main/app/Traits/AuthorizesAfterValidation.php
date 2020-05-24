<?php

namespace App\Traits;

/**
 * This trait reverses the default order of laravel's authorization logic
 * Normally laravel authorizes before validation. This trait enables validation
 * before suthorisation.
 */
trait AuthorizesAfterValidation
{
  public function aithorize()
  {
    return true;
  }

  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      if (!$validator->failed() && !$this->authorizeValidated()) {
        $this->failedAuthorization();
      }
    });
  }

  abstract public function authorizeValidated();
}
