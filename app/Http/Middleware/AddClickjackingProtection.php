<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddClickjackingProtection
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Don't apply these headers to redirects as they might interfere
        if (!$response->isRedirection()) {
            $response->headers->set('X-Frame-Options', 'DENY', false);
            $response->headers->set(
                'Content-Security-Policy',
                $this->getCspPolicy(),
                false // Don't replace existing headers
            );

            // Additional security headers
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');
        }

        return $response;
    }

    protected function getCspPolicy(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline'", // Consider adding nonces instead
            "style-src 'self' 'unsafe-inline'",
            "img-src 'self' data:",
            "font-src 'self'",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "upgrade-insecure-requests"
        ]);
    }
}