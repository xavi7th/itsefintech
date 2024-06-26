<?php

namespace App\Modules\NormalAdmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\NormalAdmin\Models\NormalAdmin;

class VerifiedNormalAdmins
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
		$admin = NormalAdmin::where('email', $request->email)->firstOrFail();
		// dd($admin->is_verified());
		if ($admin->is_verified()) {
			return $next($request);
		}
		return response()->json(['status' => 'Login limited'], 416);
	}
}
