<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JeroenG\Explorer\Application\Explored;
use Laravel\Scout\Searchable;

class Book extends Model implements Explored
{
    use SoftDeletes, Searchable;

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

    protected $with = [
        'authors',
        'type',
        'genres'
    ];

    public function searchableAs()
    {
        return 'books';
    }

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'name' => 'text',
            'publishing_house' => 'text',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'publishing_house' => $this->publishing_house,
        ];
    }

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
