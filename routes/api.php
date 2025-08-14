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
use App\Http\Controllers\UserController;
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
                Route::get('/', 'index')->name('index')
                    ->withoutMiddleware(CheckAuth::class);
                Route::get('{author}', 'show')->name('show')
                    ->withoutMiddleware(CheckAuth::class);
                Route::post('{author}', 'store')->name('store');
                Route::match(['put', 'patch'], '{author}', 'update')->name('update');
                Route::delete('{author}', 'destroy')->name('destroy');
            });

        Route::prefix('books')->name('books.')->controller(BookController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')
                    ->withoutMiddleware(CheckAuth::class);
                Route::get('{book}', 'show')->name('show')
                    ->withoutMiddleware(CheckAuth::class);
                Route::post('{book}', 'store')->name('store');
                Route::match(['put', 'patch'], '{book}', 'update')->name('update');
                Route::delete('{book}', 'destroy')->name('destroy');
            });

        Route::prefix('booktypes')->name('booktypes.')->controller(BookTypeController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')
                    ->withoutMiddleware(CheckAuth::class);
                Route::get('{booktype}', 'show')->name('show')
                    ->withoutMiddleware(CheckAuth::class);
                Route::post('{booktype}', 'store')->name('store');
                Route::match(['put', 'patch'], '{booktype}', 'update')->name('update');
                Route::delete('{booktype}', 'destroy')->name('destroy');
            });

        Route::prefix('chats')->name('chats.')->controller(ChatController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{chat}', 'show')->name('show');
                Route::post('{chat}', 'store')->name('store');
                Route::match(['put', 'patch'], '{chat}', 'update')->name('update');
                Route::delete('{chat}', 'destroy')->name('destroy');
                Route::get('{chat}/messages', 'messages')->name('messages');
                Route::post('{chat}/messages', 'sendMessage')->name('sendMessage');
            });

        Route::prefix('covers')->name('covers.')->controller(CoverController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{cover}', 'show')->name('show')
                    ->withoutMiddleware(CheckAuth::class);
                Route::post('{cover}', 'store')->name('store');
                Route::match(['put', 'patch'], '{cover}', 'update')->name('update');
                Route::delete('{cover}', 'destroy')->name('destroy');
            });

        Route::prefix('deals')->name('deals.')->controller(DealController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{deal}', 'show')->name('show');
                Route::post('{deal}', 'store')->name('store');
                Route::match(['put', 'patch'], '{deal}', 'update')->name('update');
                Route::delete('{deal}', 'destroy')->name('destroy');
            });

        Route::prefix('genres')->name('genres.')->controller(GenreController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')
                    ->withoutMiddleware(CheckAuth::class);
                Route::get('{genre}', 'show')->name('show')
                    ->withoutMiddleware(CheckAuth::class);
                Route::post('{genre}', 'store')->name('store');
                Route::match(['put', 'patch'], '{genre}', 'update')->name('update');
                Route::delete('{genre}', 'destroy')->name('destroy');
            });

        Route::prefix('photos')->name('photos.')->controller(PhotoController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{photo}', 'show')->name('show')
                    ->withoutMiddleware(CheckAuth::class);
                Route::post('{photo}', 'store')->name('store');
                Route::match(['put', 'patch'], '{photo}', 'update')->name('update');
                Route::delete('{photo}', 'destroy')->name('destroy');
            });

        Route::prefix('settings')->name('settings.')->controller(SettingController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')
                    ->withoutMiddleware(CheckAuth::class);
                Route::get('{setting}', 'show')->name('show')
                    ->withoutMiddleware(CheckAuth::class);
                Route::post('{setting}', 'store')->name('store');
                Route::match(['put', 'patch'], '{setting}', 'update')->name('update');
                Route::delete('{setting}', 'destroy')->name('destroy');
            });

        Route::prefix('users')->name('users.')->controller(UserController::class)
            ->middleware(CheckAuth::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('{user}', 'show')->name('show')
                    ->withoutMiddleware(CheckAuth::class);
                Route::post('{user}', 'store')->name('store');
                Route::match(['put', 'patch'], '{user}', 'update')->name('update');
                Route::delete('{user}', 'destroy')->name('destroy');

                Route::get('{user}/settings', 'settings')->name('settings');
                Route::patch('{user}/settings/{setting}', 'updateSetting')->name('updateSetting');

                Route::get('{user}/chats', 'chats')->name('chats');

                Route::get('{user}/messages', 'messages')->name('messages');

                Route::get('{user}/notifications', 'notifications')->name('notifications');
                Route::patch('{user}/notifications/{notification}', 'updateNotification')->name('updateNotification');
            });
    });

