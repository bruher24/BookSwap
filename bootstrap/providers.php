<?php

return [
    App\Providers\AppServiceProvider::class,
    env('APP_ENV') !== 'testing' ? App\Providers\HorizonServiceProvider::class : null,
];
