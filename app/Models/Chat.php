<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Chat extends Model
{
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'chats';

    public $fillable = [
        'first_user_id',
        'second_user_id',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
