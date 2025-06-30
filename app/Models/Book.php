<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'user_id',
        'publishing_house',
        'publication_year',
        'isbn',
        'page_count',
        'type_id',
    ];

    public $appends = [
        'mainAuthor'
    ];

    public function type()
    {
        return $this->belongsTo(BookType::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function getMainAuthorAttribute()
    {
        return $this->authors()->first()->formattedName;
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
}
