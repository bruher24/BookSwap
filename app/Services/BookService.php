<?php

namespace App\Services;

use App\Interfaces\BookServiceInterface;
use App\Models\Author;
use App\Models\Book;
use App\Models\Cover;
use App\Models\Genre;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        DB::beginTransaction();
        try {
            $data['cover_id'] = Cover::$baseCoverId;
            if (isset($data['cover'])) {
                $uuid = Str::uuid()->toString();
                $fileType = $data['cover']->getClientOriginalExtension();
                $path = Storage::disk('public')->putFileAs('covers', $data['cover'], $uuid . "." . $fileType);
                if ($path) {
                    $cover = new Cover([
                        'src' => $path,
                    ]);
                    $cover->save();
                    $data['cover_id'] = $cover->id;
                } else {
                    Log::error('Ошибка сохранения нового файла', [
                        'file' => $data['cover']
                    ]);
                }
            }

            $book = parent::create($data);
            if (!$book) {
                throw new Exception('Ошибка при создании книги');
            }

            $authors = $this->filterAuthorsData($data);
            if (!empty($authors) && !$this->attach($book, $authors)) {
                throw new Exception('Ошибка при добавлении авторов');
            }

            DB::commit();
            return $book;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage(), [
                'data' => $data,
            ]);
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
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }
}
