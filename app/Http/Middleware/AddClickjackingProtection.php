<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

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

        // $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // $response->headers->set('X-Content-Type-Options', 'nosniff');
        // $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        // $response->headers->set(
        //     'Content-Security-Policy',
        //     "default-src 'self'; " .
        //     "script-src 'self' https://cdn.jsdelivr.net; " . // Consider using nonces instead
        //     "style-src 'self' https://fonts.bunny.net; " .
        //     "font-src 'self' https://fonts.bunny.net; " .
        //     "img-src 'self' data: blob:; " .
        //     "connect-src 'self' wss://52.64.119.63; " . // For websockets if needed
        //     "frame-src 'none'; " .
        //     "frame-ancestors 'none'; " .
        //     "object-src 'none'; " .
        //     "form-action 'self'; " .
        //     "base-uri 'self'; " .
        //     "upgrade-insecure-requests;"
        // );

        // $response->headers->setCookie(
        //     cookie(
        //         'XSRF-TOKEN',
        //         csrf_token(),
        //         120,     // duration in minutes
        //         '/',
        //         null,
        //         true,    // Secure
        //         false,   // HttpOnly must be false
        //         false,
        //         'Strict' // SameSite
        //     )
        // );

        // //return $next($request);
        // $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        return $response;
    }
}
