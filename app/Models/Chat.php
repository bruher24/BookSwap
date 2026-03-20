<?php

namespace App\Models;

use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Chat extends Model implements Cacheable
{
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'chats';

    public $fillable = [
        'first_user_id',
        'second_user_id',
    ];

    public function isUserBelongs(User $user): bool
    {
        $chatUsers = [
            $this->first_user_id,
            $this->second_user_id
        ];
        return in_array($user->id, $chatUsers);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
