<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookTypeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CoverController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserChatsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFavoritesController;
use App\Http\Controllers\UserMessagesController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\UserNotificationsController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Middleware\CheckAuth;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')
    ->middleware(['auth:sanctum', 'throttle:api'])
    ->group(function () {
        Route::missing(function () {
            abort(404);
        });

        Route::prefix('auth')->name('auth.')->controller(AuthController::class)
            ->group(function () {
                Route::post('/', 'auth')->name('auth')
                    ->withoutMiddleware('auth:sanctum');
                Route::post('register', 'register')->name('register');
                Route::post('login', 'login')->name('login');
                Route::post('logout', 'logout')->name('logout')
                    ->middleware(CheckAuth::class);
            });

        Route::prefix('authors')->name('authors.')->controller(AuthorController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware(CheckAuth::class);
                Route::post('/', 'store')->name('store');
                Route::get('{author}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::match(['put', 'patch'], '{author}', 'update')->name('update');
                Route::delete('{author}', 'destroy')->name('destroy');
            });

        Route::prefix('books')->name('books.')->controller(BookController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware(CheckAuth::class);
                Route::post('/', 'store')->name('store');
                Route::get('{book}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::match(['put', 'patch'], '{book}', 'update')->name('update');
                Route::delete('{book}', 'destroy')->name('destroy');
            });

        Route::prefix('booktypes')->name('booktypes.')->controller(BookTypeController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware(CheckAuth::class);
                Route::post('/', 'store')->name('store');
                Route::get('{booktype}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::match(['put', 'patch'], '{booktype}', 'update')->name('update');
                Route::delete('{booktype}', 'destroy')->name('destroy');
            });

        Route::prefix('chats')->name('chats.')->controller(ChatController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('{chat}', 'show')->name('show');
                Route::match(['put', 'patch'], '{chat}', 'update')->name('update');
                Route::delete('{chat}', 'destroy')->name('destroy');

                Route::get('{chat}/messages', 'messages')->name('messages');
                Route::post('{chat}/messages', 'sendMessage')->name('sendMessage');
            });

        Route::prefix('covers')->name('covers.')->controller(CoverController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('{cover}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::delete('{cover}', 'destroy')->name('destroy');
            });

        Route::prefix('deals')->name('deals.')->controller(DealController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('{deal}', 'show')->name('show');
            });

        Route::prefix('genres')->name('genres.')->controller(GenreController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware(CheckAuth::class);
                Route::post('/', 'store')->name('store');
                Route::get('{genre}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::match(['put', 'patch'], '{genre}', 'update')->name('update');
                Route::delete('{genre}', 'destroy')->name('destroy');
            });

        Route::prefix('photos')->name('photos.')->controller(PhotoController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('{photo}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::delete('{photo}', 'destroy')->name('destroy');
            });

        Route::prefix('settings')->name('settings.')->controller(SettingController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware(CheckAuth::class);
                Route::get('{setting}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
            });

        Route::prefix('users')->name('users.')->controller(UserController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('{user}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::match(['put', 'patch'], '{user}', 'update')->name('update');
                Route::delete('{user}', 'destroy')->name('destroy');

                Route::prefix('{user}/chats')->name('chats.')
                    ->controller(UserChatsController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('{chat}/messages', 'messages')->name('messages');
                        Route::patch('{chat}/messages/{message}', 'read')->name('read');
                        Route::patch('{chat}/messages/', 'readAll')->name('readAll');
                    });

                Route::prefix('{user}/favorites')->name('favorites.')
                    ->controller(UserFavoritesController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'like')->name('like');
                        Route::delete('{book}', 'dislike')->name('dislike');
                    });

                Route::prefix('{user}/settings')->name('settings.')
                    ->controller(UserSettingsController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'add')->name('add');
                        Route::patch('{setting}', 'update')->name('update');
                    });

                Route::prefix('{user}/notifications')->name('notifications.')
                    ->controller(UserNotificationsController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::patch('/', 'checkAll')->name('checkAll');
                        Route::patch('{notification}', 'check')->name('check');
                    });
            });
    });

