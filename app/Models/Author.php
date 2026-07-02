<?php

namespace App\Models;

use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidation;
use Database\Factories\AuthorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

final class Author extends Model implements Cacheable
{
    /** @use HasFactory<AuthorFactory> */
    use HasFactory;
    use CacheInvalidation;
    use Searchable;
    use SoftDeletes;

    public const string CACHE_KEY = 'authors';

    public $fillable = [
        'user_id',
        'lastname',
        'firstname',
        'patronymic',
        'birthdate',
    ];

    public $appends = [
        'formattedName',
        'fullName',
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
            'created_at' => $this->created_at instanceof Carbon
                ? $this->created_at->timestamp
                : $this->created_at,
        ];
    }

    public function getFormattedNameAttribute(): string
    {
        return "$this->lastname "
            . mb_substr($this->firstname, 0, 1) . '.'
            . (isset($this->patronymic) && strlen($this->patronymic) ? ' ' . mb_substr($this->patronymic, 0, 1) . '.' : '');
    }

    public function getFullNameAttribute(): string
    {
        return "$this->lastname $this->firstname" . (isset($this->patronymic) && strlen($this->patronymic) ? " $this->patronymic" : '');
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
