<?php

namespace App\Http\Middleware;

use Closure;


class CheckForInactivity
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		/**
		 * Get the current idle time in seconds
		 */
		if ($request->session()->has('LAST_ACTIVITY')) {
			$idletime = now()->diffInSeconds(session()->get('LAST_ACTIVITY'));
		} else {
			/**
			 * Initialise idle time to 120 seconds
			 * ? Assume time for round trip web request is an average of 120 seconds
			 */
			$idletime = 120;
		}

		/**
		 * Set the idle time in a session
		 */
		// session(['IDLE_TIME' => $idletime]);

		/**
		 * Set now as the time of last activity
		 */
		session()->put('LAST_ACTIVITY', now());

		/**
		 * ! Log the user out if idle for more that specified time
		 */
		if ($idletime > config('app.permissible_idle_time')) {
			if (auth('card_user')->check()) {
				auth('card_user')->logout();
			}
		}

		return $next($request);
	}
}
