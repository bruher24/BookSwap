<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    public $fillable = [
        'user_id',
        'src',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
