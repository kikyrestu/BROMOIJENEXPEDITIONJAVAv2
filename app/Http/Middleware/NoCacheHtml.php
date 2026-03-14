<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoCacheHtml
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only apply to HTML responses (not assets, API, etc.)
        if ($response->headers->get('Content-Type') &&
            str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
