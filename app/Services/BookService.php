<?php

namespace App\Services;

use App\Models\Book;
use App\Repositories\BookRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BookService extends Service
{
    public function __construct()
    {
        parent::__construct(new BookRepository());
        $this->ucFirstFields = [
            'name',
            'publishing_house',
        ];
    }

    public function params(int $userId = null): array
    {
        if (isset($userId)) {
            $books = $this->byUser($userId);
        }
        if (!isset($books)) {
            $books = $this->getAll();
        }
        $result['genres'] = $books->flatMap->genres->unique('name');
        $result['authors'] = $books->flatMap->authors->unique(function ($author) {
            return implode('|', [
                $author->lastname,
                $author->firstname,
                $author->patronymic ?? ''
            ]);
        });
        $result['years'] = $books->pluck('publication_year')->unique();
        $result['types'] = $books->pluck('type')->filter()->unique();
        return $result ?? [];
    }

    public function byUser(int $userId, array $filters = []): Collection|false
    {
        if (method_exists($this->repository, 'byUser')) {
            return $this->repository->byUser($userId, $filters);
        }
        return false;
    }

    public function create(array $data): Model|false
    {
        $book = parent::create($data);
        if ($book) {
            $authors = $this->filterAuthorsData($data);
            $this->attach($book, $authors);
            return $book;
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
                'lastname' => $data['authorFirstname'],
                'firstname' => $data['authorLastname'],
                'patronymic' => $data['authorPatronymic'],
                'birthdate' => $data['authorBirthdate'],
            ];
        }
        return $authors;
    }

    public function attach(Book $book, array $authors): bool
    {
        if (method_exists($this->repository, 'attach')) {
            try {
                if (!empty($authors)) {
                    $this->repository->attach($book, $authors);
                }
            } catch (Exception $e) {
                dd($e->getMessage());
                logger($e->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }
}