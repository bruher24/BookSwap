<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookTypeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CoverController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFavoritesController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\UserSettingController;
use App\Http\Middleware\BearerAuthorization;
use App\Http\Middleware\IsAdmin;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')
    ->middleware(['auth:sanctum', 'throttle:api'])
    ->group(function () {
        Route::missing(function () {
            abort(404);
        });

        Route::prefix('auth')->name('auth.')->controller(AuthController::class)
            ->group(function () {
                Route::post('register', 'register')->name('register')->withoutMiddleware('auth:sanctum');
                Route::post('login', 'login')->name('login')->withoutMiddleware('auth:sanctum');
                Route::post('refresh', 'refresh')->name('refresh')->middleware(BearerAuthorization::class);
                Route::post('logout', 'logout')->name('logout')->middleware(BearerAuthorization::class);
            });

        Route::prefix('authors')->name('authors.')->controller(AuthorController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware('auth:sanctum');
                Route::post('/', 'store')->name('store');
                Route::get('{author}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{author}', 'update')->name('update');
                Route::delete('{author}', 'destroy')->name('destroy');
            });

        Route::prefix('books')->name('books.')->controller(BookController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware('auth:sanctum');
                Route::post('/', 'store')->name('store');
                Route::get('{book}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{book}', 'update')->name('update');
                Route::delete('{book}', 'destroy')->name('destroy');
            });

        Route::prefix('booktypes')->name('booktypes.')->controller(BookTypeController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware('auth:sanctum');
                Route::post('/', 'store')->name('store');
                Route::get('{booktype}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{booktype}', 'update')->name('update');
                Route::delete('{booktype}', 'destroy')->name('destroy');
            });

        Route::prefix('chats')->name('chats.')->controller(ChatController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{chat}', 'show')->name('show');
                Route::put('{chat}', 'update')->name('update');
                Route::delete('{chat}', 'destroy')->name('destroy');
            });

        Route::prefix('covers')->name('covers.')->controller(CoverController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{cover}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{cover}', 'update')->name('update')->middleware(IsAdmin::class);
                Route::delete('{cover}', 'destroy')->name('destroy');
            });

        Route::prefix('deals')->name('deals.')->controller(DealController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{deal}', 'show')->name('show');
                Route::put('{deal}', 'update')->name('update')->middleware(IsAdmin::class);
                Route::delete('{deal}', 'destroy')->name('destroy');
            });

        Route::prefix('filters')->name('filters.')->controller(FilterController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware('auth:sanctum');
                Route::post('/', 'store')->name('store')->middleware(IsAdmin::class);
                Route::get('{filter}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{filter}', 'update')->name('update')->middleware(IsAdmin::class);
                Route::delete('{filter}', 'destroy')->name('destroy')->middleware(IsAdmin::class);
            });

        Route::prefix('genres')->name('genres.')->controller(GenreController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware('auth:sanctum');
                Route::post('/', 'store')->name('store');
                Route::get('{genre}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{genre}', 'update')->name('update');
                Route::delete('{genre}', 'destroy')->name('destroy');
            });

        Route::prefix('photos')->name('photos.')->controller(PhotoController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{photo}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{photo}', 'update')->name('update')->middleware(IsAdmin::class);
                Route::delete('{photo}', 'destroy')->name('destroy');
            });

        Route::prefix('settings')->name('settings.')->controller(SettingController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store')->middleware(IsAdmin::class);
                Route::get('{setting}', 'show')->name('show');
                Route::put('{setting}', 'update')->name('update')->middleware(IsAdmin::class);
                Route::delete('{setting}', 'destroy')->name('destroy')->middleware(IsAdmin::class);
            });

        Route::prefix('users')->name('users.')->controller(UserController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{user}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{user}', 'update')->name('update');
                Route::delete('{user}', 'destroy')->name('destroy');

                Route::prefix('{user}/chats')->name('chats.')
                    ->controller(UserChatController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('{chat}/messages', 'messages')->name('messages');
                        Route::post('{chat}/messages', 'send')->name('send');
                        Route::put('{chat}/messages/{message}', 'read')->name('read');
                        Route::put('{chat}/messages', 'readMany')->name('readMany');
                    });

                Route::prefix('{user}/favorites')->name('favorites.')
                    ->controller(UserFavoritesController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('{book}', 'like')->name('like');
                        Route::delete('{book}', 'dislike')->name('dislike');
                    });

                Route::prefix('{user}/settings')->name('settings.')
                    ->controller(UserSettingController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::put('{setting}', 'update')->name('update');
                    });

                Route::prefix('{user}/notifications')->name('notifications.')
                    ->controller(UserNotificationController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('/', 'readAll')->name('readAll');
                        Route::delete('{notification}', 'read')->name('read');
                    });
            });
        Route::get('search', SearchController::class)->name('search');
    });


Scramble::registerUiRoute('docs');
Scramble::registerJsonSpecificationRoute('docs.json');
