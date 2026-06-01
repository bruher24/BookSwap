<?php

use App\Providers\AppServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\SwaggerUiServiceProvider;

return [
    AppServiceProvider::class,
    SwaggerUiServiceProvider::class,
    env('APP_ENV') !== 'testing' ? HorizonServiceProvider::class : null,
];
