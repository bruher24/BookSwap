<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BookTypeController;
use App\Http\Middleware\CheckAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;

Route::controller(MainController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
        Route::get('about', 'about')->name('about');
        Route::get('search', 'search')->name('search');
    });


Route::prefix('users/')->middleware(CheckAuth::class)->controller(UserController::class)->name('users.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('create', 'create')->name('create')->withoutMiddleware(CheckAuth::class);
        Route::post('login', 'login')->name('login')->withoutMiddleware(CheckAuth::class);
        Route::get('profile/{section?}', 'profile')->name('profile');
        Route::get('logout', 'logout')->name('logout');
        Route::match(['post', 'get'], '{user}/books', 'books')->name('books');
        Route::patch('{user}', 'update')->name('update');
        Route::delete('{user}', 'delete')->name('delete');
    });

Route::prefix('books/')->middleware(CheckAuth::class)->controller(BookController::class)->name('books.')
    ->group(function () {
        Route::match(['post', 'get'], '/', 'index')->name('index')->withoutMiddleware(CheckAuth::class);
        Route::post('store', 'store')->name('store');
        Route::get('{book}', 'show')->name('show');
        Route::get('{book}/edit', 'edit')->name('edit');
        Route::patch('{book}', 'update')->name('update');
        Route::delete('{book}', 'delete')->name('delete');
    });

Route::prefix('genres/')->controller(GenreController::class)->name('genres.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::match(['post', 'get'], '{genre}/books', 'books')->name('books');
    });

Route::prefix('authors/')->controller(AuthorController::class)->name('authors.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::match(['post', 'get'], '{author}/books', 'books')->name('books');
    });

Route::prefix('types/')->controller(BookTypeController::class)->name('types.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });


Route::prefix('api/v1/')->group(function () {
    Route::get('types', [BookTypeController::class, 'getTypes'])->name('getTypes');
    Route::get('authors', [AuthorController::class, 'getAuthors'])->name('getAuthors');
});
