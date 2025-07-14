<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cover extends Model
{
    public static int $baseCoverId = 1;
    private string $baseCoverPath = 'storage/app/public/cover.png';
    public $fillable = [
        'src'
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
