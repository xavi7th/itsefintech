<?php

namespace App\Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\Admin\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OnlyAdmins
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
		if (!Admin::canAccess()) {
			Session::flush();
			Auth::logout();

			if (request()->isJson()) {
				return response()->json(['status' => 'Unauthorised request'], 423);
			}
			return redirect()->route('admin.login')->withErrors('Unauthorised Action');
		}

		return $next($request);
	}
}
