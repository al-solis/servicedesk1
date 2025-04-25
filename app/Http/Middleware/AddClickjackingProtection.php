<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddClickjackingProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' https://cdn.jsdelivr.net; " . // Consider using nonces instead
            "style-src 'self' https://fonts.bunny.net; " .
            "font-src 'self' https://fonts.bunny.net; " .
            "img-src 'self' data: blob:; " .
            "connect-src 'self' wss://52.64.119.63; " . // For websockets if needed
            "frame-src 'none'; " .
            "frame-ancestors 'none'; " .
            "object-src 'none'; " .
            "form-action 'self'; " .
            "base-uri 'self'; " .
            "upgrade-insecure-requests;"
        );


        //return $next($request);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        return $response;
    }
}
