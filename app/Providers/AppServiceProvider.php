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
