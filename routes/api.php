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
use App\Http\Controllers\UserNotificationsController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Middleware\CheckAuth;
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
                Route::post('/', 'authenticate')->name('auth')
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
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{chat}', 'show')->name('show');
                Route::match(['put', 'patch'], '{chat}', 'update')->name('update');
                Route::delete('{chat}', 'destroy')->name('destroy');
            });

        Route::prefix('covers')->name('covers.')->controller(CoverController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{cover}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::match(['put', 'patch'], '{cover}', 'update')->name('update')->middleware(IsAdmin::class);
                Route::delete('{cover}', 'destroy')->name('destroy');
            });

        Route::prefix('deals')->name('deals.')->controller(DealController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{deal}', 'show')->name('show');
                Route::match(['put', 'patch'], '{deal}', 'update')->name('update')->middleware(IsAdmin::class);
                Route::delete('{deal}', 'destroy')->name('destroy')->middleware(IsAdmin::class);
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
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{photo}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::match(['put', 'patch'], '{photo}', 'update')->name('update')->middleware(IsAdmin::class);
                Route::delete('{photo}', 'destroy')->name('destroy');
            });

        Route::prefix('settings')->name('settings.')->controller(SettingController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store')->middleware(IsAdmin::class);
                Route::get('{setting}', 'show')->name('show');
                Route::match(['put', 'patch'], '{setting}', 'update')->name('update')->middleware(IsAdmin::class);
                Route::delete('{setting}', 'destroy')->name('destroy')->middleware(IsAdmin::class);
            });

        Route::prefix('users')->name('users.')->controller(UserController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware(IsAdmin::class);
                Route::post('/', 'store')->name('store');
                Route::get('{user}', 'show')->name('show')->withoutMiddleware(CheckAuth::class);
                Route::match(['put', 'patch'], '{user}', 'update')->name('update');
                Route::delete('{user}', 'destroy')->name('destroy');

                Route::prefix('{user}/chats')->name('chats.')
                    ->controller(UserChatsController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('{chat}/messages', 'messages')->name('messages');
                        Route::post('{chat}/messages', 'send')->name('send');
                        Route::patch('{chat}/messages/{message}', 'read')->name('read');
                        Route::patch('{chat}/messages/', 'readAll')->name('readAll');
                    });

                Route::prefix('{user}/favorites')->name('favorites.')
                    ->controller(UserFavoritesController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('{book}', 'like')->name('like');
                        Route::delete('{book}', 'dislike')->name('dislike');
                    });

                Route::prefix('{user}/settings')->name('settings.')
                    ->controller(UserSettingsController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('{setting}', 'add')->name('add');
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


Scramble::registerUiRoute('docs');
Scramble::registerJsonSpecificationRoute('docs.json');
