<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Report or log an exception.
	 *
	 * @param  \Exception  $exception
	 * @return void
	 */
	public function report(Exception $exception)
	{
		parent::report($exception);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $exception
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $exception)
	{
		// dd(get_class($exception));
		if ($request->expectsJson()) {
			if ($exception instanceof NotFoundHttpException) {
				return response()->json(['message' => 'No such endpoint'], 404);
			} elseif ($exception instanceof ModelNotFoundException) {
				if (getenv('APP_ENV') === 'local') {
					return response()->json(['Error' => $exception->getMessage()], 500);
				}
				return response()->json(['message' => 'Item not found'], 404);
			} elseif ($exception instanceof MethodNotAllowedHttpException) {
				return response()->json(['message' => 'Invalid request'], 405);
			} elseif ($exception instanceof QueryException) {
				if (getenv('APP_ENV') === 'local') {
					return response()->json(['Error' => $exception->getMessage()], 500);
				}
				return response()->json(['message' => 'Error while trying to handle request'], 500);
			}
		}

		return parent::render($request, $exception);
	}
}
