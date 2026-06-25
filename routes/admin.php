<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminAuthorController;
use App\Http\Controllers\AdminBookController;
use App\Http\Controllers\AdminBookTypeController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\AdminCoverController;
use App\Http\Controllers\AdminFilterController;
use App\Http\Controllers\AdminGenreController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\AdminPhotoController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminTradeOfferController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/v1/admin')
    ->name('api.admin.')
    ->middleware(['auth:sanctum', 'throttle:api', 'admin'])
    ->group(function () {
        Route::controller(AdminAuthController::class)
            ->group(function () {
                Route::post('login', 'login')->name('login')
                    ->withoutMiddleware(['auth:sanctum', 'admin']);
                Route::post('logout', 'logout')->name('logout');
                Route::get('profile', 'profile')->name('profile');
            });


        Route::apiResource('authors', AdminAuthorController::class);
        Route::apiResource('books', AdminBookController::class);
        Route::apiResource('book_types', AdminBookTypeController::class);
        Route::apiResource('chats', AdminChatController::class);
        Route::apiResource('covers', AdminCoverController::class);
        Route::apiResource('filters', AdminFilterController::class);
        Route::apiResource('genres', AdminGenreController::class);
        Route::apiResource('notifications', AdminNotificationController::class);
        Route::apiResource('photos', AdminPhotoController::class);
        Route::apiResource('roles', AdminRoleController::class);
        Route::apiResource('settings', AdminSettingController::class);
        Route::apiResource('trade_offers', AdminTradeOfferController::class);
        Route::apiResource('users', AdminUserController::class);
    });
