<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    public function dealItems(): HasMany
    {
        return $this->hasMany(DealItem::class);
    }
}
