<?php

namespace App\Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\Accountant\Models\Accountant;

class VerifiedAccountants
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
		$accountant = Accountant::where('email', $request->email)->firstOrFail();
		// dd($accountant->is_verified());
		if ($accountant->is_verified()) {
			return $next($request);
		}
		return response()->json(['status' => 'Login limited'], 416);
	}
}
