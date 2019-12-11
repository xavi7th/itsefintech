<?php

namespace App\Modules\SalesRep\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\SalesRep\Models\SalesRep;

class VerifiedSalesReps
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
		$sales_rep = SalesRep::where('email', $request->email)->firstOrFail();
		if ($sales_rep->is_verified()) {
			return $next($request);
		}
		return response()->json(['status' => 'Login limited'], 416);
	}
}
