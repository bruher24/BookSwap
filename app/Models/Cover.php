<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Cover extends Model
{
    use SoftDeletes;

    private string $baseCoverPath = 'storage/app/public/cover.png';
    public static int $baseCoverId = 1;
    public $fillable = [
        'src'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
