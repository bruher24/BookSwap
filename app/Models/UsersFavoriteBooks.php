<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersFavoriteBooks extends Model
{
    use SoftDeletes;

    public $fillable = [
        'user_id',
        'book_id',
    ];
}
