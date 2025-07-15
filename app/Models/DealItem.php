<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class DealItem extends Model
{
    use SoftDeletes;

    public $fillable = [
        'deal_id',
        'book_id',
    ];

    protected $with = [
        'deal',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}
