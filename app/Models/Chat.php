<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;

    public $fillable = [
        'first_user_id',
        'second_user_id',
    ];

    public function first_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'first_user_id');
    }

    public function second_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'second_user_id');
    }

    public function current_user(User $user): User
    {
        return match (true) {
            $user->is($this->first_user) => $this->first_user()->first(),
            $user->is($this->second_user) => $this->second_user()->first(),
        };
    }

    public function other_user(User $user): User
    {
        return match (true) {
            $user->isNot($this->first_user) => $this->first_user()->first(),
            $user->isNot($this->second_user) => $this->second_user()->first(),
        };
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
