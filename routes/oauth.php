<?php

use App\Http\Controllers\OAuth\AuthorizationController;
use App\Http\Controllers\OAuth\UserInfoController;
use App\Http\Controllers\OAuth\OpenIdConfigurationController;
use App\Http\Controllers\OAuth\JwksController;
use Illuminate\Support\Facades\Route;

// OpenID Connect discovery endpoints (public)
Route::get('/.well-known/openid-configuration', [OpenIdConfigurationController::class, '__invoke']);
Route::get('/.well-known/jwks.json', [JwksController::class, '__invoke']);

// OAuth 2.0 authorization endpoint (requires authentication)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize'])
        ->name('oauth.authorize');
    
    Route::post('/oauth/authorize', [AuthorizationController::class, 'approve'])
        ->name('oauth.approve');
    
    Route::delete('/oauth/authorize', [AuthorizationController::class, 'deny'])
        ->name('oauth.deny');
});

// Token endpoint (public, but requires client credentials)
Route::post('/oauth/token', [\Laravel\Passport\Http\Controllers\AccessTokenController::class, 'issueToken'])
    ->middleware('throttle');

// UserInfo endpoint (protected by Bearer token)
Route::middleware(['auth:api'])->group(function () {
    Route::get('/oauth/userinfo', [UserInfoController::class, '__invoke']);
});

