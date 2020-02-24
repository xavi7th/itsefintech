<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
	/**
	 * Get the path the user should be redirected to when they are not authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return string
	 */
	protected function redirectTo($request)
	{
		foreach (collect(config('auth.guards'))->except(['api', 'card_user']) as $key => $value) {
			auth($key)->logout();
		}
		if ($request->expectsJson()) {
			return response()->json(['message' => 'Unauthenticated'], 401);
		} else {
			abort(403, 'Please login to continue');
			// return route('accountant.login');
		}
	}
}
