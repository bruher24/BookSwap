<?php

namespace App\Services;

use App\Interfaces\BookServiceInterface;
use App\Models\Book;
use App\Models\Cover;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class BookService extends Service implements BookServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod}
     */
    public function __construct()
    {
        parent::__construct(Book::class);
        $this->ucFirstFields = [
            'name',
            'publishing_house',
        ];
    }

    #[Override]
    public function create(array $data): Book|false
    {
        DB::beginTransaction();
        try {
            $data['cover_id'] = Cover::BASE_COVER_ID;
            if (isset($data['cover'])) {
                // TODO: пускать ивент создания обложки
            }

            $book = parent::create($data);
            if (!$book) {
                throw new Exception('Ошибка при создании книги');
            }

            $authors = $this->filterAuthorsData($data);
            if (!empty($authors) && !$this->attach($book->id, $authors)) {
                throw new Exception('Ошибка при добавлении авторов');
            }

            DB::commit();
            return $book;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    #[Override]
    public function get(string $id): Book|false
    {
        return parent::get($id);
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

    #[Override]
    public function attach(string $book_id, array $authors): bool
    {
        try {
            $book = $this->get($book_id);

            if (!$book instanceof Book) {
                throw new Exception('Ошибка получения книги');
            }

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
}
