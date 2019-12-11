<?php

namespace App\Modules\DispatchAdmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Modules\DispatchAdmin\Models\DispatchAdmin;

class OnlyDispatchAdmins
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
		if (!DispatchAdmin::canAccess()) {
			Session::flush();
			Auth::logout();

			if (request()->wantsJson()) {
				return response()->json(['status' => 'Unauthorised request'], 423);
			}
			return redirect()->route('dispatchadmin.login')->withErrors('Unauthorised Action');
		}

		return $next($request);
	}
}
