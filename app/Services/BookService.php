<?php

namespace App\Services;

use App\Interfaces\BookServiceInterface;
use App\Models\Book;
use App\Models\Cover;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class BookService implements BookServiceInterface
{
    private array $ucFirstFields = [
        'name',
        'publishing_house',
    ];

    #[Override]
    public function create(array $data): Book|false
    {
        try {
            return DB::transaction(function () use ($data) {
                if (isset($data['cover'])) {
                    $coverData = [
                        'user_id' => $data['user_id'],
                        'file' => $data['cover'],
                    ];

                    $cover = (new CoverService())->create($coverData);
                    unset($data['cover']);
                }

                $data['cover_id'] = isset($cover) && $cover instanceof Cover
                    ? $cover->id
                    : Cover::BASE_COVER_ID;

                $formattedData = $this->formatData($data);
                $book = Book::create($formattedData);
                $book->authors()->attachOrFail($data['authors_ids']);
                return $book->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): Book|false
    {
        try {
            return Book::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(Book::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Book::CACHE_KEY);
                return Book::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function where(string $field, string $value): Collection
    {
        try {
            return Book::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function update(Book $book, array $data): Book|false
    {
        try {
            return DB::transaction(function () use ($book, $data) {
                if (isset($data['cover'])) {
                    $coverData = [
                        'user_id' => $book->user_id,
                        'file' => $data['cover'],
                    ];

                    $cover = (new CoverService())->create($coverData);
                    $data['cover_id'] = $cover ? $cover->id : Cover::BASE_COVER_ID;
                    unset($data['cover']);
                }

                $formattedData = $this->formatData($data);
                $book->updateOrFail($formattedData);

                if (!empty($data['authors_ids'])) {
                    $book->authors()->syncOrFail($data['authors_ids']);
                }

                return $book->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function delete(Book $book): bool
    {
        try {
            $book->deleteOrFail();
            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function filtered(array $filters): Collection
    {
        if (empty($filters)) {
            return Book::query()
                ->where('is_available', 1)
                ->withoutTrashed()
                ->get();
        }

        try {
            ksort($filters);
            $cacheKey = Book::CACHE_KEY . http_build_query($filters);

            return Cache::remember($cacheKey, 600, function () use ($filters, $cacheKey): Collection {
                Log::debug("Stored in cache: " . $cacheKey);

                $query = Book::query()
                    ->withoutTrashed()
                    ->where('is_available', '1')
                    ->when(!empty($filters['name']), function (Builder $q) use ($filters) {
                        $q->where('name', 'like', '%' . $filters['name'] . '%');
                    })
                    ->when(!empty($filters['publishing_house']), function (Builder $q) use ($filters) {
                        $q->whereIn('publishing_house', $filters['publishing_house']);
                    })
                    ->when(!empty($filters['publication_year']), function (Builder $q) use ($filters) {
                        $q->whereIn('publication_year', $filters['publication_year']);
                    })
                    ->when(!empty($filters['page_count']), function (Builder $q) use ($filters) {
                        $q->whereBetween('page_count', [$filters['page_count'][0], $filters['page_count'][1]]);
                    })
                    ->when(!empty($filters['book_type_id']), function (Builder $q) use ($filters) {
                        $q->whereIn('book_type_id', $filters['book_type_id']);
                    })
                    ->when(!empty($filters['author_id']), function (Builder $q) use ($filters) {
                        $q->whereHas('authors', function (Builder $authorQ) use ($filters) {
                            $authorQ->whereIn('authors.id', $filters['author_id']);
                        });
                    })
                    ->when(!empty($filters['genre_id']), function (Builder $q) use ($filters) {
                        $q->whereHas('genres', function (Builder $genreQ) use ($filters) {
                            $genreQ->whereIn('genres.id', $filters['genre_id']);
                        });
                    });

                Log::debug($query->toRawSql());
                return $query->get();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    public function byUser(User $user): Collection
    {
        return $user->books()
            ->withoutTrashed()
            ->get();
    }

    private function formatData(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (in_array($key, $this->ucFirstFields)) {
                $value = ucfirst($value);
            }
        }
        return $data;
    }
}
