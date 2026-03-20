<?php

namespace App\Models;

use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidation;
use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Notification extends Model implements Cacheable
{
    /** @use HasFactory<NotificationFactory> */
    use HasFactory;
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'notifications';

    public $fillable = [
        'subject',
        'body',
        'user_id',
        'seen'
    ];
}
