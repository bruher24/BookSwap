<?php

namespace App\Providers;

use App\Interfaces\AuthorServiceInterface;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\GenreServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Services\AuthorService;
use App\Services\BookService;
use App\Services\GenreService;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthorServiceInterface::class, AuthorService::class);
        $this->app->bind(BookServiceInterface::class, BookService::class);
        $this->app->bind(GenreServiceInterface::class, GenreService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Guard $auth): void
    {
        date_default_timezone_set('Europe/Samara');

        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });

        View::composer('*', function ($view) use ($auth) {
            $view->with('user', $auth->user());
        });

        Queue::failing(function (JobFailed $event) {
            Log::error(
                "Queue: " . $event->job->getQueue()
                . "\n Error: " . $event->exception->getMessage()
            );
        });
    }
}
