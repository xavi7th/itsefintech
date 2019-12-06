<?php

namespace App\Modules\CardUser\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OnlyVerifiedUsers
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{

		if (!Auth::appuser()->is_verified()) {
			Session::flush();
			Auth::logout();

			if (request()->ajax()) {
				return response()->json(['message' => 'Unverified user'], 416);
			}
			return redirect()->route('home')->withErrors('Unverified user');
		}

		return $next($request);
	}
}
