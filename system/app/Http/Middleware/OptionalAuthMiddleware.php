<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($token = $request->bearerToken()) {
            try {
                if (Auth::guard('user')->check()) {
                    $user = Auth::guard('user')->user();
                    // Log or dump user information for debugging
                    Log::info('Authenticated user:', ['id' => $user->id, 'email' => $user->email]);
                } else {
                    Log::warning('Bearer token provided but authentication failed');
                }
            } catch (\Exception $e) {
                Log::error('Authentication error: ' . $e->getMessage());
            }
        } else {
            Log::info('No bearer token provided');
        }

        return $next($request);
    }
}
