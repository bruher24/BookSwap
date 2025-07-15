<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

final class Author extends Model
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

    protected $with = [
        'books',
    ];

    public function searchableAs(): string
    {
        return 'authors';
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => (string)$this->id,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'created_at' => $this->created_at->timestamp,
        ];
    }

    public function getFormattedNameAttribute(): string
    {
        return "$this->lastname "
            . mb_substr($this->firstname, 0, 1) . "."
            . ($this->patronymic ? mb_substr($this->patronymic, 0, 1) : '');
    }

    public function getFullNameAttribute(): string
    {
        return "$this->lastname $this->firstname" . ($this->patronymic ? " $this->patronymic" : '');
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }
}
