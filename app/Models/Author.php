<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    public $fillable = [
        'lastname',
        'firstname',
        'patronymic',
        'birthdate',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

}
