<?php

namespace App\Modules\AccountOfficers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\AccountOfficer\Models\AccountOfficer;

class VerifiedAccountOfficers
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
		$account_officer = AccountOfficer::where('email', $request->email)->firstOrFail();
		if ($account_officer->is_verified()) {
			return $next($request);
		}
		return response()->json(['status' => 'Login limited'], 416);
	}
}
