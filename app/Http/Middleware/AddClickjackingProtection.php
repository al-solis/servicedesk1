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
            "default-src 'self'; 
    script-src 'self' 'unsafe-inline' https://52.64.119.63 https://cdn.jsdelivr.net; 
    style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; 
    object-src 'none'; 
    base-uri 'self'; 
    frame-ancestors 'none'; 
    font-src 'self' https://cdn.jsdelivr.net "
        );
        //return $next($request);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        return $response;
    }
}
