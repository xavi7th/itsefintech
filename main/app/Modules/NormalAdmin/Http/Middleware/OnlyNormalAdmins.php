<?php

namespace App\Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Modules\NormalAdmin\Models\NormalAdmin;

class OnlyNormalAdmins
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
		if (!NormalAdmin::canAccess()) {
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
