<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JeroenG\Explorer\Application\Explored;
use Laravel\Scout\Searchable;

class Author extends Model implements Explored
{
    use SoftDeletes, Searchable;

    public $fillable = [
        'lastname',
        'firstname',
        'patronymic',
        'birthdate',
    ];

    public $appends = [
        'formattedName',
        'fullName',
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

    public function getFullNameAttribute()
    {
        return "$this->lastname $this->firstname" . ($this->patronymic ? " $this->patronymic" : '');
    }

    public function searchableAs()
    {
        return 'authors';
    }

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'lastname' => 'text',
            'firstname' => 'text',
            'patronymic' => 'text',
            'fullName' => 'text'
        ];
    }

//    public function toSearchableArray(): array
//    {
//        return [
//            'id' => $this->id,
//            'lastname' => $this->lastname,
//            'firstname' => $this->firstname,
//            'patronymic' => $this->patronymic,
//            'fullName' => $this->fullName,
//        ];
//    }
}
