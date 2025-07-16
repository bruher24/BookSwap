<?php

namespace App\Services;

use App\Interfaces\BookServiceInterface;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class BookService extends Service implements BookServiceInterface
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

    public function byUser(User $user, array $conditions = []): array
    {
        $books = $this->where($conditions)->where('user_id', $user->id);
        $allBooks = Book::where('user_id', $user->id)->get();
        $params = $this->params($allBooks);

        return [$books, $params] ?? [];
    }

    public function byAuthor(Author $author, array $conditions = []): array
    {
        $conditions['authors'] = [$author->id];
        $books = $this->where($conditions);
        $allBooks = $this->where(['authors' => [$author->id]]);
        $params = $this->params($allBooks);
        unset($params['authors']);

        return [$books, $params] ?? [];
    }

    public function byGenre(Genre $genre, array $conditions = []): array
    {
        $conditions['genres'] = [$genre->id];
        $books = $this->where($conditions);
        $allBooks = $this->where(['genres' => [$genre->id]]);
        $params = $this->params($allBooks);
        unset($params['genres']);

        return [$books, $params] ?? [];
    }

    public function where(array $conditions = []): Collection
    {
        if (empty($conditions)) {
            return $this->getAll();
        }

        $books = Book::where('deleted_at', null);

        if (isset($conditions['names'])) {
            $books->where('name', 'like', '%' . $conditions['names'] . '%');
        }

        if (isset($conditions['publishing_houses'])) {
            $books->where('publishing_house', 'like', '%' . $conditions['publishing_houses'] . '%');
        }

        if (isset($conditions['genres'])) {
            $genres = $this->genreService->getMany($conditions['genres']);
            if ($genres->isNotEmpty()) {
                $books->whereHas('genres', function ($query) use ($genres) {
                    $query->whereIn('id', $genres->pluck('id'));
                });
            }
        }

        if (isset($conditions['authors'])) {
            $authors = $this->authorService->getMany($conditions['authors']);
            if ($authors->isNotEmpty()) {
                $books->whereHas('authors', function ($query) use ($authors) {
                    $query->whereIn('id', $authors->pluck('id'));
                });
            }
        }

        if (isset($conditions['years'])) {
            $books->whereIn('publication_year', $conditions['years']);
        }

        if (isset($conditions['book_types'])) {
            $books->whereIn('book_type', $conditions['book_types']);
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