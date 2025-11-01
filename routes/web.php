<?php

use Illuminate\Support\Facades\Route;

Route::get('coverage', function () {
    $html = file_get_contents(__DIR__ . '/../coverage/index.html');
    if ($html === false) {
        $html = '';
    }
    return response($html, 200)->header('Content-Type', 'text/html');
});

Route::get('info', function () {
    phpinfo();
});
