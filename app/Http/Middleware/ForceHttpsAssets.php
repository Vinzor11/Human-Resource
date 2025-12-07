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
        if (config('app.env') === 'production' && $request->isSecure()) {
            // Replace HTTP asset URLs with HTTPS
            $content = $response->getContent();
            
            if ($content) {
                $appUrl = config('app.url');
                $httpUrl = str_replace('https://', 'http://', $appUrl);
                
                // Replace HTTP URLs with HTTPS for assets
                $content = str_replace($httpUrl, $appUrl, $content);
                
                // Also replace any http:// URLs in build/assets paths
                $content = preg_replace(
                    '/http:\/\/([^"\']*build\/assets\/[^"\']*)/i',
                    'https://$1',
                    $content
                );
                
                $response->setContent($content);
            }
        }

        return $response;
    }
}

