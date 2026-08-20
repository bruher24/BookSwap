<?php

namespace App\Models;

use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidationTrait;
use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Message extends Model implements Cacheable
{
    /** @use HasFactory<MessageFactory> */
    use HasFactory;
    use SoftDeletes;
    use CacheInvalidationTrait;

    public const string CACHE_KEY = 'messages';

    public $fillable = [
        'chat_id',
        'sender_id',
        'subject',
        'body',
        'seen'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}
