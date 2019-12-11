<?php

namespace App\Modules\CustomerSupport\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Modules\CustomerSupport\Models\CustomerSupport;

class OnlyCustomerSupports
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
		if (!CustomerSupport::canAccess()) {
			Session::flush();
			Auth::logout();

			if (request()->wantsJson()) {
				return response()->json(['status' => 'Unauthorised request'], 423);
			}
			return redirect()->route('customersupport.login')->withErrors('Unauthorised Action');
		}

		return $next($request);
	}
}
