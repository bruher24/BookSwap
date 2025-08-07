<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheInvalidation
{
    public static function bootCacheInvalidation(): void
    {
        static::saved(function ($model) {
            Cache::forget(get_class($model));
        });

        static::deleted(function ($model) {
            Cache::forget(get_class($model));
        });

        static::restored(function ($model) {
            Cache::forget(get_class($model));
        });
    }
}
