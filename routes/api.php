<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookTypeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CoverController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')
    ->name('api.')
//    ->middleware('auth:sanctum')
    ->middleware('throttle:api')
    ->group(function () {
        Route::missing(function () {
            abort(404);
        });

        Route::prefix('auth')
            ->name('auth.')
            ->controller(AuthController::class)
            ->group(function () {
                Route::post('/auth', [AuthController::class, 'auth'])
                    ->name('auth')
                    ->withoutMiddleware('auth:sanctum');

                Route::post('/register', [AuthController::class, 'register'])
                    ->name('register');

                Route::post('/login', [AuthController::class, 'login'])
                    ->name('login');

                Route::post('/logout', [AuthController::class, 'logout'])
                    ->name('logout');
//                    ->middleware(CheckAuth::class);
            });

        Route::resource('authors', AuthorController::class)
            ->names('authors')
            ->except(['index', 'show']);
//            ->middleware(CheckAuth::class);
        Route::resource('authors', AuthorController::class)
            ->names('authors')
            ->only(['index', 'show']);

        Route::resource('books', BookController::class)
            ->names('books')
            ->except(['index', 'show']);
//            ->middleware(CheckAuth::class);
        Route::resource('books', BookController::class)
            ->names('books')
            ->only(['index', 'show']);

        Route::resource('chats', ChatController::class)
            ->names('chats');
//            ->middleware(CheckAuth::class);

        Route::resource('chats.messages', MessageController::class)
            ->shallow()
            ->names('messages');
//            ->middleware(CheckAuth::class);

        Route::resource('covers', CoverController::class)
            ->names('covers')
            ->except(['show']);
//            ->middleware(CheckAuth::class);
        Route::resource('covers', CoverController::class)
            ->names('covers')
            ->only(['show']);

        Route::resource('deals', DealController::class)
            ->names('deals');
//            ->middleware(CheckAuth::class);

        Route::resource('genres', GenreController::class)
            ->names('genres')
            ->except(['index', 'show']);
//            ->middleware(CheckAuth::class);
        Route::resource('genres', GenreController::class)
            ->names('genres')
            ->only(['index', 'show']);

        Route::resource('photos', PhotoController::class)
            ->names('photos')
            ->except(['show']);
//            ->middleware(CheckAuth::class);
        Route::resource('photos', PhotoController::class)
            ->names('photos')
            ->only(['show']);

        Route::resource('settings', SettingController::class)
            ->names('settings');
//            ->middleware(CheckAuth::class);

        Route::resource('types', BookTypeController::class)
            ->names('types')
            ->except(['index', 'show']);
//            ->middleware(CheckAuth::class);
        Route::resource('types', BookTypeController::class)
            ->names('types')
            ->only(['index', 'show']);

        Route::get('/users/{user}/messages/unread', [UserController::class, 'unread']);

        Route::resource('users', UserController::class)
            ->names('users');
//            ->middleware(CheckAuth::class);

        Route::resource('users.notifications', NotificationController::class)
            ->names('notifications');
//            ->middleware(CheckAuth::class);
    });

