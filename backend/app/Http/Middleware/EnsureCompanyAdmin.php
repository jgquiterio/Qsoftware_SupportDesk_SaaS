<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_active || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized. Administrator access required.',
            ], 403);
        }

        return $next($request);
    }
}