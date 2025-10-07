<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use SoftDeletes, CacheInvalidation;

    const string CACHE_KEY = 'settings';

    public $fillable = [
        'name',
        'label',
        'description',
        'available_values',
    ];

    protected function availableValues(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => explode(',', $value),
            set: fn(array $value) => implode(',', $value),
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
