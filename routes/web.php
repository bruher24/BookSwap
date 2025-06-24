<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;

Route::controller(MainController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('about', 'about')->name('about');
});


Route::prefix('users')->controller(UserController::class)->name('users.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/create', 'create')->name('create');
    Route::post('/login', 'login')->name('login');
    Route::get('/profile/{section?}', 'profile')->name('profile');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/{user}/books', 'books')->name('books');
    Route::patch('/{user}', 'update')->name('update');
    Route::delete('/{user}', 'destroy')->name('destroy');
});

Route::prefix('books')->controller(BookController::class)->name('books.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/{book}', 'show')->name('show');
    Route::get('/{book}/edit', 'edit')->name('edit');
    Route::patch('/{book}', 'update')->name('update');
    Route::delete('/{book}', 'destroy')->name('destroy');
});

Route::prefix('genres')->controller(GenreController::class)->name('genres.')->group(function () {
   Route::get('/', 'index')->name('index');
});

Route::prefix('authors')->controller(AuthorController::class)->name('authors.')->group(function () {
    Route::get('/', 'index')->name('index');
});
