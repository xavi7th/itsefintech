<?php

namespace App\Modules\DispatchAdmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\DispatchAdmin\Models\DispatchAdmin;

class VerifiedDispatchAdmins
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
		$dispatch_admin = DispatchAdmin::where('email', $request->email)->firstOrFail();
		if ($dispatch_admin->is_verified()) {
			return $next($request);
		}
		return response()->json(['status' => 'Login limited'], 416);
	}
}
