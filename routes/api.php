<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookTypeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CoverController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TradeOfferController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')
    ->middleware(['auth:sanctum', 'throttle:api'])
    ->group(function () {
        Route::prefix('admin')->name('admin.')->controller(AdminAuthController::class)
            ->middleware('admin')
            ->group(function () {
                Route::post('login', 'login')->name('login')->withoutMiddleware(['auth:sanctum', 'admin']);
                Route::post('logout', 'logout')->name('logout');
                Route::get('me', 'me')->name('me');
            });

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
                Route::patch('{author}', 'update')->name('update');
                Route::delete('{author}', 'destroy')->name('destroy');
            });

        Route::prefix('books')->name('books.')->controller(BookController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware('auth:sanctum');
                Route::post('/', 'store')->name('store');
                Route::get('where', 'where')->name('where')->withoutMiddleware('auth:sanctum');
                Route::get('{book}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::patch('{book}', 'update')->name('update');
                Route::delete('{book}', 'destroy')->name('destroy');
            });

        Route::prefix('book_types')->name('book_types.')->controller(BookTypeController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware('auth:sanctum');
                Route::post('/', 'store')->name('store')->middleware('admin');
                Route::get('{book_type}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                Route::patch('{book_type}', 'update')->name('update')->middleware('admin');
                Route::delete('{book_type}', 'destroy')->name('destroy')->middleware('admin');
            });

        Route::prefix('chats')->name('chats.')->controller(ChatController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware('admin');
                Route::post('/', 'store')->name('store');
                Route::get('{chat}', 'show')->name('show');
                Route::delete('{chat}', 'destroy')->name('destroy')->middleware('admin');
                Route::get('{chat}/messages', 'messages')->name('messages');
                Route::post('{chat}/messages', 'send')->name('send');
                Route::patch('{chat}/block', 'block')->name('block');
            });

        Route::prefix('covers')->name('covers.')->controller(CoverController::class)
            ->middleware('admin')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store')->withoutMiddleware('admin');
                Route::get('{cover}', 'show')->name('show');
                Route::delete('{cover}', 'destroy')->name('destroy');
            });

        Route::prefix('filters')->name('filters.')->controller(FilterController::class)
            ->middleware('admin')
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware(['admin', 'auth:sanctum']);
                Route::post('/', 'store')->name('store');
                Route::get('{filter}', 'show')->name('show')->withoutMiddleware(['admin', 'auth:sanctum']);
                Route::patch('{filter}', 'update')->name('update');
                Route::delete('{filter}', 'destroy')->name('destroy')->withoutMiddleware('admin');
            });

        Route::prefix('genres')->name('genres.')->controller(GenreController::class)
            ->middleware('admin')
            ->group(function () {
                Route::get('/', 'index')->name('index')->withoutMiddleware(['admin', 'auth:sanctum']);
                Route::post('/', 'store')->name('store');
                Route::get('{genre}', 'show')->name('show')->withoutMiddleware(['admin', 'auth:sanctum']);
                Route::patch('{genre}', 'update')->name('update');
                Route::delete('{genre}', 'destroy')->name('destroy');
            });

        Route::prefix('messages')->name('messages.')->controller(MessageController::class)
            ->middleware('admin')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{message}', 'show')->name('show');
                Route::patch('{message}', 'update')->name('update');
                Route::delete('{message}', 'destroy')->name('destroy');
            });

        Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)
            ->middleware('admin')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{notification}', 'show')->name('show');
                Route::patch('{notification}', 'update')->name('update');
                Route::delete('{notification}', 'destroy')->name('destroy');
                Route::patch('{notification}/read', 'read')->name('read')->withoutMiddleware('admin');
            });

        Route::prefix('photos')->name('photos.')->controller(PhotoController::class)
            ->middleware('admin')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store')->withoutMiddleware('admin');
                Route::get('{photo}', 'show')->name('show');
                Route::delete('{photo}', 'destroy')->name('destroy');
            });

        Route::prefix('roles')->name('roles.')->controller(RoleController::class)
            ->middleware('admin')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{role}', 'show')->name('show');
                Route::patch('{role}', 'update')->name('update');
                Route::delete('{role}', 'destroy')->name('destroy');
            });

        Route::get('search', SearchController::class)->name('search')->withoutMiddleware('auth:sanctum');

        Route::prefix('settings')->name('settings.')->controller(SettingController::class)
            ->middleware('admin')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('{setting}', 'show')->name('show')->withoutMiddleware('admin');
                Route::patch('{setting}', 'update')->name('update');
                Route::delete('{setting}', 'destroy')->name('destroy');
                Route::patch('update_for_user/{setting}', 'updateForUser')->name('updateForUser')->withoutMiddleware('admin');
            });

        Route::prefix('trade_offers')->name('trade_offers.')->controller(TradeOfferController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index')->middleware('admin');
                Route::post('/', 'store')->name('store');
                Route::get('{trade_offer}', 'show')->name('show');
                Route::patch('{trade_offer}', 'update')->name('update');
                Route::delete('{trade_offer}', 'destroy')->name('destroy')->middleware('admin');
                Route::get('{trade_offer}/items', 'items')->name('items');
                Route::patch('{trade_offer}/update_status', 'updateStatus')->name('updateStatus');
            });

        Route::controller(UserController::class)
            ->group(function () {
                Route::prefix('users')->name('users.')
                    ->group(function () {
                        Route::get('/', 'index')->name('index')->middleware('admin');
                        Route::post('/', 'store')->name('store')->middleware('admin');
                        Route::get('{user}', 'show')->name('show')->withoutMiddleware('auth:sanctum');
                        Route::patch('{user}', 'update')->name('update');
                        Route::delete('{user}', 'destroy')->name('destroy');
                        Route::patch('{user}/rate', 'rate')->name('rate');
                    });

                Route::prefix('me')->name('me.')
                    ->group(function () {
                        Route::get('/', 'me')->name('me');
                        Route::get('favorites', 'favorites')->name('favorites');
                        Route::patch('like/{book}', 'like')->name('like');
                        Route::patch('dislike/{book}', 'dislike')->name('dislike');
                        Route::get('authors', 'authors')->name('authors');
                        Route::get('books', 'books')->name('books');
                        Route::get('chats', 'chats')->name('chats');
                        Route::get('notifications', 'notifications')->name('notifications');
                        Route::get('settings', 'settings')->name('settings');
                        Route::get('trade_history', 'tradeHistory')->name('tradeHistory');
                    });
            });
    });
