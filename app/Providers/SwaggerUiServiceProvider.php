<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * @psalm-suppress UnusedClass
 */
final class SwaggerUiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('viewSwaggerUI', function (User $user = null) {
            return isset($user) && $user->isAdmin();
        });
    }
}
