<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class BookType extends Model
{
    use SoftDeletes, Searchable;

    public $fillable = [
        'name',
    ];

    public function searchableAs(): string
    {
        return 'book_types';
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => (string)$this->id,
            'name' => $this->name,
            'created_at' => $this->created_at->timestamp,
        ];
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
