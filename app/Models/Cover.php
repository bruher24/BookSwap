<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Cover extends Model
{
    public static int $baseCoverId = 1;
    private string $baseCoverPath = 'storage/app/public/cover.png';
    public $fillable = [
        'src'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
