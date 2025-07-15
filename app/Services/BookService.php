<?php

namespace App\Services;

use App\Models\Book;
use Exception;
use Illuminate\Database\Eloquent\Collection;

final class BookService extends Service
{
    public function __construct(
        private readonly AuthorService $authorService,
        private readonly GenreService $genreService
    ) {
        parent::__construct(Book::class);
        $this->ucFirstFields = [
            'name',
            'publishing_house',
        ];
    }

    public function byUser(int $userId, array $conditions = []): array
    {
        $books = $this->where($conditions)->where('user_id', $userId);
        $allBooks = Book::where('user_id', $userId)->get();
        $params = $this->params($allBooks);

        return [$books, $params] ?? [];
    }

    public function byAuthor(int $authorId, array $conditions = []): array
    {
        $books = $this->where($conditions);
        $allBooks = $this->where(['author' => [$authorId]]);
        $params = $this->params($allBooks);

        return [$books, $params] ?? [];
    }

    public function byGenre(int $genreId, array $conditions = []): array
    {
        $books = $this->where($conditions);
        $allBooks = $this->where(['genre' => [$genreId]]);
        $params = $this->params($allBooks);

        return [$books, $params] ?? [];
    }

    public function where(array $conditions = []): Collection
    {
        if (empty($conditions)) {
            return $this->getAll();
        }

        $books = Book::where('deleted_at', null);

        if (isset($conditions['name'])) {
            $books->where('name', 'like', '%' . $conditions['name'] . '%');
        }

        if (isset($conditions['publishing_house'])) {
            $books->where('publishing_house', 'like', '%' . $conditions['publishing_house'] . '%');
        }

        if (isset($conditions['genre'])) {
            $genres = $this->genreService->getMany($conditions['genre']);
            if ($genres->isNotEmpty()) {
                $books->whereHas('genres', function ($query) use ($genres) {
                    $query->whereIn('id', $genres->pluck('id'));
                });
            }
        }

        if (isset($conditions['author'])) {
            $authors = $this->authorService->getMany($conditions['author']);
            if ($authors->isNotEmpty()) {
                $books->whereHas('authors', function ($query) use ($authors) {
                    $query->whereIn('id', $authors->pluck('id'));
                });
            }
        }

        if (isset($conditions['year'])) {
            $books->whereIn('publication_year', $conditions['year']);
        }

        if (isset($conditions['type'])) {
            $books->whereIn('type_id', $conditions['type']);
        }
        return $books->get();
    }

    public function create(array $data): Book|false
    {
        $book = parent::create($data);
        if (!$book) {
            return false;
        }
        $authors = $this->filterAuthorsData($data);
        // TODO: проверить логику attach
        if (!empty($authors)) {
            if ($this->attach($book, $authors)) {
                return $book;
            }
        }
        return false;
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
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }
}