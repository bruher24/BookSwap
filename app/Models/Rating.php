<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Rating extends Model
{
    public $fillable = [
        'user_id',
        'rater_id',
        'rate',
    ];

    protected $with = [
        'user',
        'rater',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rater(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
