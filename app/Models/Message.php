<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    
    public $fillable = [
        'from_id',
        'to_id',
        'subject',
        'body',
    ];
}
