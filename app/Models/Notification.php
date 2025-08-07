<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes, CacheInvalidation;

    public $fillable = [
        'subject',
        'body',
        'user_id',
        'seen'
    ];
}
