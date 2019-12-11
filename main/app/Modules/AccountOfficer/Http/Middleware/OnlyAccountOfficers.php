<?php

namespace App\Modules\AccountOfficer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Modules\AccountOfficer\Models\AccountOfficer;

class OnlyAccountOfficers
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
		if (!AccountOfficer::canAccess()) {
			Session::flush();
			Auth::logout();

			if (request()->wantsJson()) {
				return response()->json(['status' => 'Unauthorised request'], 423);
			}
			return redirect()->route('accountofficer.login')->withErrors('Unauthorised Action');
		}

		return $next($request);
	}
}
