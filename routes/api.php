<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookTypeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CoverController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TradeOfferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFavoritesController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\UserSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')
    ->middleware(['auth:sanctum', 'throttle:api'])
    ->group(function () {
        Route::prefix('auth')->name('auth.')->controller(AuthController::class)
            ->group(function () {
                Route::post('register', 'register')->name('register')->withoutMiddleware('auth:sanctum');
                Route::post('login', 'login')->name('login')->withoutMiddleware('auth:sanctum');
                Route::post('logout', 'logout')->name('logout');
                Route::get('verify_email/{userId}', 'verifyEmail')->name('verifyEmail')->withoutMiddleware('auth:sanctum');
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
                Route::get('where', 'where')->name('where')->withoutMiddleware('auth:sanctum');
                Route::get('{book}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{book}', 'update')->name('update');
                Route::delete('{book}', 'destroy')->name('destroy');
            });

        Route::prefix('book_types')->name('book_types.')->controller(BookTypeController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware('auth:sanctum');
                Route::post('/', 'store')->name('store');
                Route::get('{book_type}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{book_type}', 'update')->name('update');
                Route::delete('{book_type}', 'destroy')->name('destroy');
            });

        Route::prefix('chats')->name('chats.')->controller(ChatController::class)
            ->group(function () {
                Route::post('/', 'store')->name('store');
                Route::get('{chat}', 'show')->name('show');
                Route::delete('{chat}', 'destroy')->name('destroy');
                Route::patch('{chat}/block', 'block')->name('block');
                Route::get('by_user/{user}', 'byUser')->name('byUser');
                Route::get('{chat}/messages', 'messages')->name('messages');
                Route::post('{chat}/messages', 'send')->name('send');
            });

        Route::prefix('covers')->name('covers.')->controller(CoverController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{cover}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{cover}', 'update')->name('update');
                Route::delete('{cover}', 'destroy')->name('destroy');
            });

        Route::prefix('filters')->name('filters.')->controller(FilterController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware('auth:sanctum');
                Route::post('/', 'store')->name('store');
                Route::get('{filter}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{filter}', 'update')->name('update');
                Route::delete('{filter}', 'destroy')->name('destroy');
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
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{photo}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{photo}', 'update')->name('update');
                Route::delete('{photo}', 'destroy')->name('destroy');
            });

        Route::get('search', SearchController::class)->name('search')->withoutMiddleware('auth:sanctum');

        Route::prefix('settings')->name('settings.')->controller(SettingController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{setting}', 'show')->name('show');
                Route::put('{setting}', 'update')->name('update');
                Route::delete('{setting}', 'destroy')->name('destroy');
            });

        Route::prefix('trade_offers')->name('trade_offers.')->controller(TradeOfferController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{trade_offer}', 'show')->name('show');
                Route::put('{trade_offer}', 'update')->name('update');
                Route::delete('{trade_offer}', 'destroy')->name('destroy');
                Route::get('{trade_offer}/items', 'items')->name('items');
                Route::get('by_sender/{sender}', 'bySender')->name('bySender');
                Route::get('by_receiver/{receiver}', 'byReceiver')->name('byReceiver');
                Route::patch('{trade_offer}/accept', 'accept')->name('accept');
                Route::patch('{trade_offer}/reject', 'reject')->name('reject');
            });

        Route::prefix('users')->name('users.')->controller(UserController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{user}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::put('{user}', 'update')->name('update');
                Route::delete('{user}', 'destroy')->name('destroy');

                Route::prefix('{user}/favorites')->name('favorites.')
                    ->controller(UserFavoritesController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::post('{book}', 'like')->name('like');
                        Route::delete('{book}', 'dislike')->name('dislike');
                    });

                Route::prefix('{user}/notifications')->name('notifications.')
                    ->controller(UserNotificationController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                    });

                Route::prefix('{user}/settings')->name('settings.')
                    ->controller(UserSettingController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::put('{setting}', 'update')->name('update');
                    });
            });
    });
