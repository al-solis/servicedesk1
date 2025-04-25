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
        // $response->headers->set(
        //     'Content-Security-Policy',
        //     "default-src 'self'; " .
        //     "script-src 'self'; " .
        //     "style-src 'self' https://fonts.bunny.net; " . // Allow Bunny Fonts
        //     "font-src 'self' https://fonts.bunny.net; " .  // Allow Bunny Fonts files
        //     "img-src 'self' data:; " .
        //     "connect-src 'self'; " .
        //     "frame-ancestors 'none'; " .
        //     "base-uri 'self';"
        // );

        // $response->headers->set(
        //     'Content-Security-Policy',
        //     "default-src 'self'; " .
        //     "script-src 'self' 'unsafe-inline' https://52.64.119.63; " .
        //     "style-src 'self' 'unsafe-inline' https://fonts.bunny.net; " .
        //     "font-src 'self' https://fonts.bunny.net; " .
        //     "img-src 'self' data: blob:; " .
        //     "connect-src 'self' https://52.64.119.63; " .
        //     "form-action 'self'; " .
        //     "frame-ancestors 'none'; " .
        //     "base-uri 'self'; " .
        //     "manifest-src 'self'; " .
        //     "worker-src 'self' blob:; " .
        //     "media-src 'self' blob:;"
        // );

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
