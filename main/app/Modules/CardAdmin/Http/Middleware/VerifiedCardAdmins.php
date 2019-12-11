<?php

namespace App\Modules\CardAdmin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\CardAdmin\Models\CardAdmin;

class VerifiedCardAdmins
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
		$account_officer = CardAdmin::where('email', $request->email)->firstOrFail();
		if ($account_officer->is_verified()) {
			return $next($request);
		}
		return response()->json(['status' => 'Login limited'], 416);
	}
}
