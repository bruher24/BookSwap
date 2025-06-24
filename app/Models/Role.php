<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $fillable = [
        'name',
    ];

    public const int ADMIN_ROLE_ID = 1;
    public const int USER_ROLE_ID = 2;
}
