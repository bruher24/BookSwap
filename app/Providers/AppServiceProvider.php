<?php

namespace App\Providers;

use App\Interfaces\AuthorServiceInterface;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\BookTypeServiceInterface;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\CoverServiceInterface;
use App\Interfaces\FilterServiceInterface;
use App\Interfaces\GenreServiceInterface;
use App\Interfaces\MessageServiceInterface;
use App\Interfaces\PhotoServiceInterface;
use App\Interfaces\RoleServiceInterface;
use App\Interfaces\SettingServiceInterface;
use App\Interfaces\TradeOfferServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Author;
use App\Models\Book;
use App\Models\Message;
use App\Models\TradeOffer;
use App\Models\User;
use App\Observers\AuthorObserver;
use App\Observers\BookObserver;
use App\Observers\MessageObserver;
use App\Observers\TradeOfferObserver;
use App\Observers\UserObserver;
use App\Services\AuthorService;
use App\Services\AuthService;
use App\Services\BookService;
use App\Services\BookTypeService;
use App\Services\ChatService;
use App\Services\CoverService;
use App\Services\FilterService;
use App\Services\GenreService;
use App\Services\MessageService;
use App\Services\PhotoService;
use App\Services\RoleService;
use App\Services\SettingService;
use App\Services\TradeOfferService;
use App\Services\UserService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Override;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->app->bind(AuthorServiceInterface::class, AuthorService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(BookServiceInterface::class, BookService::class);
        $this->app->bind(BookTypeServiceInterface::class, BookTypeService::class);
        $this->app->bind(ChatServiceInterface::class, ChatService::class);
        $this->app->bind(CoverServiceInterface::class, CoverService::class);
        $this->app->bind(FilterServiceInterface::class, FilterService::class);
        $this->app->bind(GenreServiceInterface::class, GenreService::class);
        $this->app->bind(MessageServiceInterface::class, MessageService::class);
        $this->app->bind(PhotoServiceInterface::class, PhotoService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(SettingServiceInterface::class, SettingService::class);
        $this->app->bind(TradeOfferServiceInterface::class, TradeOfferService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        date_default_timezone_set('Europe/Samara');

        Author::observe(AuthorObserver::class);
        Book::observe(BookObserver::class);
        Message::observe(MessageObserver::class);
        TradeOffer::observe(TradeOfferObserver::class);
        User::observe(UserObserver::class);

        RateLimiter::for('api', function (Request $request) {
            $user = $request->user();
            $limitBy = $user instanceof User ? $user->id : $request->ip();
            return Limit::perSecond(10)->by($limitBy);
        });

        Queue::failing(function (JobFailed $event) {
            Log::error(
                "Queue: " . $event->job->getQueue()
                . "\n Error: " . $event->exception->getMessage()
            );
        });
    }
}
