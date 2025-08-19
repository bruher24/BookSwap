<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Filter extends Model
{
    use SoftDeletes, CacheInvalidation;

    public $fillable = [
        'name',
        'by_fields',
        'disabled',
    ];
}
