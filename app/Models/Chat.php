<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes, CacheInvalidation;

    const string CACHE_KEY = 'chats';

    public $fillable = [
        'first_user_id',
        'second_user_id',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
