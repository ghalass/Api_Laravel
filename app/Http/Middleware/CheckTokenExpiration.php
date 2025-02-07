<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the authenticated user
        $user = $request->user();

        // Get the token expiration date
        $tokenExpiration = $user->tokens->first()->expires_at;

        // Check if the token has expired
        if ($tokenExpiration && Carbon::now()->greaterThan($tokenExpiration)) {
            return response()->json(['message' => 'Token expired'], 401);
        }

        return $next($request);
    }
}
