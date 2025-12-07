<?php

namespace App\Providers;

use App\Models\RequestSubmission;
use App\Observers\LeaveRequestObserver;
use App\Observers\CertificateGenerationObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
            
            // Ensure request is detected as HTTPS
            // This helps Vite generate HTTPS URLs
            if (request()->header('X-Forwarded-Proto') === 'https' || 
                request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
                $_SERVER['HTTPS'] = 'on';
                $_SERVER['SERVER_PORT'] = '443';
            }
            
            // Use view composer to ensure Vite URLs are HTTPS
            View::composer('*', function ($view) {
                // This ensures the view has access to HTTPS URLs
                if (function_exists('vite')) {
                    // Force HTTPS for asset URLs
                    $assetUrl = env('ASSET_URL', config('app.url'));
                    if (str_starts_with($assetUrl, 'http://')) {
                        $assetUrl = str_replace('http://', 'https://', $assetUrl);
                    }
                    config(['app.asset_url' => $assetUrl]);
                }
            });
        }

        // Register Leave Request Observer
        RequestSubmission::observe(LeaveRequestObserver::class);
        
        // Register Certificate Generation Observer
        RequestSubmission::observe(CertificateGenerationObserver::class);

        // Configure Passport
        Passport::tokensExpireIn(now()->addHours(1));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        
        // Define scopes for different systems
        Passport::tokensCan([
            'accounting' => 'Access accounting system',
            'payroll' => 'Access payroll system',
            'hr' => 'Access HR system',
            'openid' => 'OpenID Connect',
            'profile' => 'Access profile information',
            'email' => 'Access email address',
        ]);
        
        // Default scope
        Passport::setDefaultScope(['hr']);
    }
}
