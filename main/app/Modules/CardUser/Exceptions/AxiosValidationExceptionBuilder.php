<?php

namespace App\Modules\CardUser\Exceptions;

use Exception;
use Illuminate\Validation\Validator;

class AxiosValidationExceptionBuilder extends Exception
{
	protected $validator;

	protected $code = 422;

	public function __construct(Validator $validator)
	{
		$this->validator = $validator;
	}

	/**
	 * Build the response to be returned
	 *
	 * @return response
	 */
	public function render()
	{
		return response()->json([
			"error" => "form validation error",
			"message" => $this->validator->errors()
			// "message" => implode('<br>', $this->validator->errors()->all())
		], $this->code);
	}
}
