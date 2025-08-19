<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookType extends Model
{
    use SoftDeletes, CacheInvalidation;

    public $fillable = [
        'name',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
