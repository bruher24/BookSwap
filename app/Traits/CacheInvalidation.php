<?php

namespace App\Traits;

use App\Models\Chat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait CacheInvalidation
{
    public static function bootCacheInvalidation(): void
    {
        static::saved(function ($model) {
            Cache::forget(get_class($model));
            Log::debug('Forgot: ' . get_class($model));
        });

        static::deleted(function ($model) {
            Cache::forget(get_class($model));
            Log::debug('Forgot: ' . get_class($model));
        });

        static::restored(function ($model) {
            Cache::forget(get_class($model));
            Log::debug('Forgot: ' . get_class($model));
        });
    }
}
