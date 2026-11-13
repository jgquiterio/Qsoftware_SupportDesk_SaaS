<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveCompanyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
	$user = $request->user();

	if (!$user || !$user->is_active) {
	    return response()->json([
	        'message' => 'Your account is inactive or unauthorized.'
	    ], 403);
	}

	if (!$user->company_id) {
	    return response()->json([
	        'message' => 'You must belong to a company to access this resource'
	    ], 403);
	}

        return $next($request);
    }
}
