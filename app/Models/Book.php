<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'publishing_house',
        'publication_year',
        'isbn',
        'page_count',
        'type_id',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function type()
    {
        return $this->belongsTo(BookType::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
