<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Message extends Model
{
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'messages';

    public $fillable = [
        'chat_id',
        'from_id',
        'to_id',
        'subject',
        'body',
        'seen'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
