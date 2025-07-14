<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    public static int $basePhotoId = 1;
    public $fillable = [
        'src',
    ];
    private string $basePhotoPath = 'storage/app/public/avatar.png';

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
