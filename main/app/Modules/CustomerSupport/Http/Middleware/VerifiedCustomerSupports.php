<?php

namespace App\Modules\CustomerSupport\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\CustomerSupport\Models\CustomerSupport;

class VerifiedCustomerSupports
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
		$customer_support = CustomerSupport::where('email', $request->email)->firstOrFail();
		if ($customer_support->is_verified()) {
			return $next($request);
		}
		return response()->json(['status' => 'Login limited'], 416);
	}
}
