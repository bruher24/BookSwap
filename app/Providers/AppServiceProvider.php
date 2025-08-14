<?php

namespace App\Providers;

use App\Interfaces\AuthorServiceInterface;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\CoverServiceInterface;
use App\Interfaces\GenreServiceInterface;
use App\Interfaces\MessageServiceInterface;
use App\Interfaces\NotificationServiceInterface;
use App\Interfaces\PhotoServiceInterface;
use App\Interfaces\SettingServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use App\Services\AuthorService;
use App\Services\AuthService;
use App\Services\BookService;
use App\Services\ChatService;
use App\Services\CoverService;
use App\Services\GenreService;
use App\Services\MessageService;
use App\Services\NotificationService;
use App\Services\PhotoService;
use App\Services\SettingService;
use App\Services\UserService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\RateLimiter;
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
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(BookServiceInterface::class, BookService::class);
        $this->app->bind(ChatServiceInterface::class, ChatService::class);
        $this->app->bind(CoverServiceInterface::class, CoverService::class);
        $this->app->bind(GenreServiceInterface::class, GenreService::class);
        $this->app->bind(MessageServiceInterface::class, MessageService::class);
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
        $this->app->bind(PhotoServiceInterface::class, PhotoService::class);
        $this->app->bind(SettingServiceInterface::class, SettingService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Guard $auth): void
    {
        RateLimiter::for('global', function (Request $request) {
            return Limit::perSecond(3)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perSecond(3)->by($request->user()?->id ?: $request->ip());
        });

        date_default_timezone_set('Europe/Samara');

        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('crud-itself', function (User $user, User $model) {
            return $user->is($model);
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
