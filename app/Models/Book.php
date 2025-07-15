<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'cover_id',
    ];
    public $appends = [
        'mainAuthor'
    ];

    protected $with = [
        'type',
        'genres',
        'authors',
        'cover',
    ];

    public function searchableAs(): string
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

    public function getMainAuthorAttribute(): string
    {
        return $this->authors()->first()->formattedName;
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(BookType::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function cover(): HasOne
    {
        return $this->hasOne(Cover::class);
    }
}
