<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersFavoriteBooks extends Model
{
    use SoftDeletes, CacheInvalidation;

    public $fillable = [
        'user_id',
        'book_id',
    ];
}
