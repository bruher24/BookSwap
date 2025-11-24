<?php

namespace App\Traits;

use App\Interfaces\Cacheable;
use App\Models\Chat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait CacheInvalidation
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public static function bootCacheInvalidation(): void
    {
        static::saved(function (Cacheable $model) {
            Cache::forget($model::CACHE_KEY);
            Log::debug('Forgot: ' . $model::CACHE_KEY);
            if ($model instanceof Chat) {
                Cache::forget($model->first_user_id . '_chats');
                Log::debug('Forgot: ' . $model->first_user_id . '_chats');
            }
        });

        static::deleted(function (Cacheable $model) {
            Cache::forget($model::CACHE_KEY);
            Log::debug('Forgot: ' . $model::CACHE_KEY);
            if ($model instanceof Chat) {
                Cache::forget($model->first_user_id . '_chats');
                Log::debug('Forgot: ' . $model->first_user_id . '_chats');
            }
        });

        static::restored(function (Cacheable $model) {
            Cache::forget($model::CACHE_KEY);
            Log::debug('Forgot: ' . $model::CACHE_KEY);
            if ($model instanceof Chat) {
                Cache::forget($model->first_user_id . '_chats');
                Log::debug('Forgot: ' . $model->first_user_id . '_chats');
            }
        });
    }
}
