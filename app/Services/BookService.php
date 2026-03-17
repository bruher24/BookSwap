<?php

namespace App\Services;

use App\Interfaces\BookServiceInterface;
use App\Models\Book;
use App\Models\Cover;
use Exception;
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
            $data['cover_id'] = Cover::BASE_COVER_ID;

            if (isset($data['cover'])) {
                $coverService = new CoverService();
                $coverService->create($data['cover']);
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

    public function where(string $field, string $value): Collection
    {
        try {
            return Book::where($field, $value)->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function update(Book $book, array $data): Book|false
    {
        try {
            DB::beginTransaction();
            $book->updateOrFail($data);
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
