<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealItem extends Model
{
    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}
