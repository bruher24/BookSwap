<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    public const int ADMIN_ROLE_ID = 1;

    public const int USER_ROLE_ID = 2;

    public $fillable = [
        'name',
    ];

    protected $with = [
        'users',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
