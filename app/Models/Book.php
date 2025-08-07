<?php

namespace App\Models;

use App\Enums\BookTypeEnum;
use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use SoftDeletes, Searchable, CacheInvalidation;

    public $fillable = [
        'name',
        'user_id',
        'publishing_house',
        'publication_year',
        'isbn',
        'page_count',
        'book_type',
        'cover_id',
    ];
    public $appends = [
        'mainAuthor'
    ];

    public function casts(): array
    {
        return [
            'book_type' => BookTypeEnum::class,
        ];
    }

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

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function cover(): BelongsTo
    {
        return $this->belongsTo(Cover::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
