<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    public function boot(Guard $auth): void
    {
        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });

        View::composer('*', function ($view) use ($auth) {
            $view->with('user', $auth->user());
        });
    }
}
