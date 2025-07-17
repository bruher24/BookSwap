<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cover extends Model
{
    use SoftDeletes;

    private string $baseCoverPath = 'storage/app/public/cover.png';

    public static int $baseCoverId = 1;

    public $fillable = [
        'src'
    ];

    public function book(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
