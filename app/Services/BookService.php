<?php

namespace App\Services;

use App\Interfaces\BookServiceInterface;
use App\Models\Book;
use App\Models\Cover;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BookService implements BookServiceInterface
{
    protected array $ucFirstFields = [
        'name',
        'publishing_house',
    ];

    private function formatData(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (in_array($key, $this->ucFirstFields)) {
                $value = ucfirst($value);
            }
        }
        return $data;
    }

    public function create(array $data): Book|false
    {
        try {
            DB::beginTransaction();

            if (isset($data['cover'])) {
                $coverData = [
                    'user_id' => $data['user_id'],
                    'src' => $data['cover']->getPathname(),
                ];

                $cover = (new CoverService())->create($coverData);
                $data['cover_id'] = $cover ? $cover->id : Cover::BASE_COVER_ID;
                unset($data['cover']);
            } else {
                $data['cover_id'] = Cover::BASE_COVER_ID;
            }

            $formattedData = $this->formatData($data);
            $book = new Book($formattedData);

            if (!$book->save()) {
                throw new Exception('Ошибка при создании книги');
            }

            $authors = $this->filterAuthorsData($data);

            if (!empty($authors) && !$this->attach($book, $authors)) {
                throw new Exception('Ошибка при добавлении авторов');
            }

            DB::commit();
            return $book->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    private function filterAuthorsData(array $data): array
    {
        $authors = [];

        for ($i = 0; $i < 4; $i++) {
            if (isset($data['author_id' . ($i == 0 ? '' : $i)])) {
                $authors['ids'][] = $data['author_id' . ($i == 0 ? '' : $i)];
            }
        }
        if (isset($data['authorFirstname'])) {
            $authors['new'] = [
                'lastname' => $data['authorLastname'],
                'firstname' => $data['authorFirstname'],
                'patronymic' => $data['authorPatronymic'],
                'birthdate' => $data['authorBirthdate'],
            ];
        }
        return $authors;
    }

    public function attach(Book $book, array $authors): bool
    {
        try {
            if (isset($authors['ids'])) {
                $book->authors()->attach($authors['ids']);
            }

            if (isset($authors['new'])) {
                $book->authors()->create($authors['new']);
            }

            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    private function detach(Book $book, array $authors = []): bool
    {
        try {
            $book->authors()->detach(!empty($authors) ? $authors : null);
            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function get(string $id): Book|false
    {
        try {
            return Book::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getAll(): Collection
    {
        try {
            return Cache::remember(Book::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Book::CACHE_KEY);
                return Book::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function where(array $filters): Collection
    {
        try {
            return Cache::remember(Book::CACHE_KEY . '_query', 600, function () use ($filters): Collection {
                Log::debug("Stored in cache: " . Book::CACHE_KEY . "_query");

                $query = Book::query()
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
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function update(Book $book, array $data): Book|false
    {
        try {
            DB::beginTransaction();

            if (isset($data['cover'])) {
                $coverData = [
                    'user_id' => $data['user_id'],
                    'src' => $data['cover']->getPathname(),
                ];

                $cover = (new CoverService())->create($coverData);
                $data['cover_id'] = $cover ? $cover->id : Cover::BASE_COVER_ID;
                unset($data['cover']);
            }

            $formattedData = $this->formatData($data);
            $book->updateOrFail($formattedData);

            $authors = $this->filterAuthorsData($data);

            if (!empty($authors)) {
                if (!$this->detach($book) || !$this->attach($book, $authors)) {
                    throw new Exception('Ошибка при добавлении авторов');
                }
            }

            DB::commit();
            return $book->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function delete(Book $book): bool
    {
        try {
            DB::beginTransaction();
            $book->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
