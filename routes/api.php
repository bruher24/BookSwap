<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookTypeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
});

Route::post('login', [UserController::class, 'APIlogin'])->name('api.login');

Route::prefix('/v1/')->middleware('auth:sanctum')->group(function () {
    Route::get('types', [BookTypeController::class, 'getTypes'])->name('getTypes');
    Route::get('authors', [AuthorController::class, 'getAuthors'])->name('getAuthors');
    Route::get('books/{book}', [BookController::class, 'getBookData'])->name('getBookData');
    Route::get('users/{user}/chat/{recipient}', [ChatController::class, 'getMessages'])->name('getMessages');
    Route::post('users/{user}/chat/{recipient}/message', [ChatController::class, 'sendMessage'])->name('sendMessage');
    Route::patch('users/{user}/notifications/{notification}', [UserController::class, 'checkOne'])->name('checkOne');
    Route::patch('users/{user}/notifications/check-many', [UserController::class, 'checkMany'])->name('checkMany');
    Route::patch('users/{user}/notifications/check-all', [UserController::class, 'checkAll'])->name('checkAll');
    Route::get('users/{user}/messages', [UserController::class, 'getUnreadMessages'])->name('getUnreadMessages');
    Route::patch('users/{user}/messages/read', [UserController::class, 'readMessages'])->name('readMessages');
});
