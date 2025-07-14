<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Book extends Model
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
    protected $casts = [
        'name' => 'string',
        'user_id' => 'integer',
        'publishing_house' => 'string',
        'publication_year' => 'integer',
        'isbn' => 'string',
        'page_count' => 'integer',
        'type_id' => 'integer',
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

    public function toSearchableArray(): array
    {
        return [
            'id' => (string)$this->id,
            'name' => $this->name,
            'publishing_house' => $this->publishing_house,
            'created_at' => $this->created_at->timestamp,
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
