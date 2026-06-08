<?php

namespace App\Models;

use App\Enums\BookCondition;
use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidation;
use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

final class Book extends Model implements Cacheable
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;
    use SoftDeletes;
    use Searchable;
    use CacheInvalidation;

    public const string CACHE_KEY = 'books';

    public $fillable = [
        'name',
        'user_id',
        'publishing_house',
        'publication_year',
        'isbn',
        'page_count',
        'condition',
        'book_type_id',
        'cover_id',
        'is_available',
        'trade_offer_id',
    ];

    protected $casts = [
        'condition' => BookCondition::class,
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

    public function book_type(): BelongsTo
    {
        return $this->belongsTo(BookType::class);
    }

    public function trade_offer(): BelongsTo
    {
        return $this->belongsTo(TradeOffer::class);
    }
}
