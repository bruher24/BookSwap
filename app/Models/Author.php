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

    public $appends = [
        'formattedName',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function getFormattedNameAttribute()
    {
        return "$this->lastname "
            . mb_substr($this->firstname, 0, 1) . "."
            . ($this->patronymic ? mb_substr($this->patronymic, 0, 1) : '');
    }

}
