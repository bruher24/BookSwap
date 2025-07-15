<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Photo extends Model
{
    use SoftDeletes;

    public static int $basePhotoId = 1;

    private string $basePhotoPath = 'storage/app/public/avatar.png';

    public $fillable = [
        'src',
    ];

    protected $with = [
        'users',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
