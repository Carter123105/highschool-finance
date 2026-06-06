<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        /*
        |------------------------------------------------------
        | SUPER ADMIN GLOBAL OVERRIDE (CRITICAL FIX)
        |------------------------------------------------------
        |
        | Admin bypasses ALL permissions and gates.
        | This fixes:
        | - limited dashboard issue
        | - sidebar restriction issue
        | - @can() blocking admin
        |
        */

        Gate::before(function ($user, $ability) {
            if ($user && $user->hasRole('Admin')) {
                return true;
            }
        });
    }
}