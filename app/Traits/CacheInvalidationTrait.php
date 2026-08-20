<?php

namespace App\Traits;

use App\Interfaces\Cacheable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait CacheInvalidationTrait
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function bootCacheInvalidationTrait(): void
    {
        static::saved(function (Cacheable $model) {
            Cache::forget($model::CACHE_KEY);
            Log::debug('Forgot: ' . $model::CACHE_KEY);
        });

        static::deleted(function (Cacheable $model) {
            Cache::forget($model::CACHE_KEY);
            Log::debug('Forgot: ' . $model::CACHE_KEY);
        });

        static::restored(function (Cacheable $model) {
            Cache::forget($model::CACHE_KEY);
            Log::debug('Forgot: ' . $model::CACHE_KEY);
        });
    }
}
