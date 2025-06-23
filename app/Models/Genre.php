<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}
