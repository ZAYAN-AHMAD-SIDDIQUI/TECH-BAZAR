<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthenticateSession;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustHosts;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\ValidateSignature;
use App\Http\Middleware\VerifyCsrfToken;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // Handle CORS
        \Illuminate\Http\Middleware\HandleCors::class,
        // Trust proxies
        \App\Http\Middleware\TrustProxies::class,
        // Prevent requests during maintenance mode
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        // Validate post size
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        // Trim strings
        \App\Http\Middleware\TrimStrings::class,
        // Convert empty strings to null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // Encrypt cookies
            \App\Http\Middleware\EncryptCookies::class,
            // Add queued cookies to response
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            // Start session
            \Illuminate\Session\Middleware\StartSession::class,
            // Share errors from session
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // Verify CSRF token
            \App\Http\Middleware\VerifyCsrfToken::class,
            // Substitute bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Throttle requests
            'throttle:api',
            // Substitute bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // Authenticate
        'auth' => \App\Http\Middleware\Authenticate::class,
        'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class,
        // Authenticate with basic auth
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        // Cache headers
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        // Authorize
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        // Redirect if authenticated
       'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'admin.guest' => \App\Http\Middleware\AdminRedirectIfAuthenticated::class,
        // Require password confirmation
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        // Validate signature
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        // Throttle requests
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // Ensure email is verified
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}
