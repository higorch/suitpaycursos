<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('student', function ($user) {
            return $user->role === 'student';
        });

        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('creator', function ($user) {
            return $user->role === 'creator';
        });
    }
}
