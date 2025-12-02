<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log the request if user is authenticated
        if ($request->user()) {
            $module = $this->extractModule($request);
            $action = $this->extractAction($request);
            
            if ($module && $action) {
                app(\App\Services\ActivityLogService::class)->log(
                    $action,
                    $module,
                    "User accessed {$request->path()}",
                    [
                        'method' => $request->method(),
                        'path' => $request->path(),
                        'status_code' => $response->getStatusCode(),
                    ]
                );
            }
        }

        return $response;
    }

    /**
     * Extract module from request
     */
    private function extractModule(Request $request): ?string
    {
        $path = $request->path();
        $segments = explode('/', $path);
        
        // Assuming URL structure: /module/...
        return $segments[0] ?? null;
    }

    /**
     * Extract action from request
     */
    private function extractAction(Request $request): string
    {
        return match($request->method()) {
            'GET' => 'view',
            'POST' => 'create',
            'PUT', 'PATCH' => 'update',
            'DELETE' => 'delete',
            default => 'access',
        };
    }
}
