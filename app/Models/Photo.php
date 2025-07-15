<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Photo extends Model
{
    use SoftDeletes;

    public static int $basePhotoId = 1;
    public $fillable = [
        'src',
    ];
    private string $basePhotoPath = 'storage/app/public/avatar.png';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
