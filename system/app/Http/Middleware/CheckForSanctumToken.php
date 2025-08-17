<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class CheckForSanctumToken
{

    public function handle($request, Closure $next)
    {
        if ($token = $request->cookie('adminAuthToken')) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }

}
