<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * HOME PATH
     */
    public const HOME = '/dashboard';

    /**
     * Bootstrap services
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | API RATE LIMITER
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('api', function (Request $request) {

            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );

        });

        /*
        |--------------------------------------------------------------------------
        | ROUTES
        |--------------------------------------------------------------------------
        */

        $this->routes(function () {

            /*
            |--------------------------------------------------------------------------
            | API ROUTES
            |--------------------------------------------------------------------------
            */

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            /*
            |--------------------------------------------------------------------------
            | WEB ROUTES
            |--------------------------------------------------------------------------
            */

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

        });
    }
}