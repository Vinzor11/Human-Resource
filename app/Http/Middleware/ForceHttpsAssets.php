<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsAssets
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only in production
        if (config('app.env') === 'production') {
            $content = $response->getContent();
            
            if ($content) {
                $appUrl = config('app.url');
                $httpUrl = str_replace('https://', 'http://', $appUrl);
                $domain = parse_url($appUrl, PHP_URL_HOST);
                
                // Replace all HTTP URLs with HTTPS for this domain
                // This handles assets, API endpoints, and any other URLs
                $content = str_replace($httpUrl, $appUrl, $content);
                
                // Also replace any http:// URLs for this domain (more comprehensive)
                $content = preg_replace(
                    '/http:\/\/([^\/]*' . preg_quote($domain, '/') . '[^"\']*)/i',
                    'https://$1',
                    $content
                );
                
                // Replace in JSON strings (for Inertia/Ziggy)
                $content = preg_replace(
                    '/"http:\/\/([^\/]*' . preg_quote($domain, '/') . '[^"]*)"/i',
                    '"https://$1"',
                    $content
                );
                
                $response->setContent($content);
            }
        }

        return $response;
    }
}

