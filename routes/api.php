<?php

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

Route::prefix('/v1')->name('api.')->middleware('auth:sanctum')->middleware('throttle:api')->group(function () {
    Route::missing(function () {
        abort(404);
    });

    Route::post('login', [UserController::class, 'APIlogin'])->name('login')->withoutMiddleware('auth:sanctum');

    Route::resources([
        'authors' => AuthorController::class,
        'chats' => ChatController::class,
        'covers' => CoverController::class,
        'deals' => DealController::class,
        'genres' => GenreController::class,
        'photos' => PhotoController::class,
        'types' => BookTypeController::class,
        'users' => UserController::class,
    ]);

    Route::resource('chats.messages', MessageController::class)->shallow();
    Route::resource('users.settings', SettingController::class);
    Route::resource('users.notifications', NotificationController::class);
    Route::resource('authors.books', BookController::class)->shallow();

    // TODO: GET chats/{chat_id}/messages - list of all messages (paginated?)
    Route::get('users/{user}/chat/{recipient}', [ChatController::class, 'getMessages'])->name('getMessages');

    // TODO: POST chats/{chat_id}/messages
    Route::post('users/{user}/chat/{recipient}/message', [ChatController::class, 'sendMessage'])->name(
        'sendMessage'
    );

    // TODO: PATCH users/{user_id}/notifications/{notification_id}
    Route::patch('users/{user}/notifications/{notification}', [UserController::class, 'checkOne'])->name(
        'checkOne'
    );

    // TODO: PATCH users/{user_id}/notifications ????
    Route::patch('users/{user}/notifications/check-many', [UserController::class, 'checkMany'])->name('checkMany');

    // TODO: PATCH users/{user_id}/notifications ????
    Route::patch('users/{user}/notifications/check-all', [UserController::class, 'checkAll'])->name('checkAll');

    // TODO: GET users/{user_id}/messages
    Route::get('users/{user}/messages', [UserController::class, 'getUnreadMessages'])->name('getUnreadMessages');

    // TODO: PATCH users/{user_id}/messages ????
    Route::patch('users/{user}/messages/read', [UserController::class, 'readMessages'])->name('readMessages');
});

