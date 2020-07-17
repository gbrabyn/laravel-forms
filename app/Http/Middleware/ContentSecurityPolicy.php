<?php
namespace App\Http\Middleware;

/**
 * Content Security Policy header
 *
 * @author G Brabyn
 */
class ContentSecurityPolicy 
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Content-Security-Policy', 'frame-ancestors \'self\'');

        return $response;
    }
}
