<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureZISAuthenticated
{
     /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('zis')->check()) {
            return $next($request);
        }
        
        return redirect()->route('zis.login');
    }
}
