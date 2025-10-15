<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PreventFlash
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only add minimal headers for optimization
        if ($response instanceof Response) {
            // Add minimal cache headers only
            $response->headers->set('Cache-Control', 'public, max-age=300');
        }

        return $response;
    }
}
