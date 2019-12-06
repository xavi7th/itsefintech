<?php

namespace App\Modules\CardUser\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OnlyCardUsers
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

		if (!CardUser::canAccess()) {
			Session::flush();
			Auth::logout();

			if (request()->ajax()) {
				return response()->json(['status' => 'Unauthorised request'], 423);
			}
			return redirect()->route('login')->withErrors('Unauthorised Action');
		}

		return $next($request);
	}
}
