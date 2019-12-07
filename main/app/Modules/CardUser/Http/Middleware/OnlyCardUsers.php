<?php

namespace App\Modules\CardUser\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Modules\CardUser\Models\CardUser;

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
			auth()->logout();
			return response()->json(['status' => 'Forbbiden'], 403);
		}

		return $next($request);
	}
}
