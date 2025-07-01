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

    public function byUser(int $userId, array $filters = []): array
    {
        if (method_exists($this->repository, 'byUser')) {
            $books = $this->repository->byUser($userId, $filters);
            $allBooks = $this->getAll();
            $params = $this->params($allBooks);
        }
        return [$books, $params] ?? [];
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
                logger($e->getMessage());
                return false;
            }
            return true;
        }
        return false;
    }

    public function search(string $search): Collection | false
    {
        $words = explode(' ', $search);
        foreach ($words as &$word) {
            $word = trim($word);
            $word = mb_ucfirst(mb_strtolower($word));

            $found = $this->find($word);
            dd($found['field']);
            return $found;
        }
        return false;
    }

    private function find($word) {
        $fields = [
            'name',
            'publishing_house',
            'firstname',
            'lastname',
            'patronymic',
        ];
        foreach ($fields as $field) {
            $found = $this->where([
                $field => $word,
            ]);
            if ($found->count() > 0) {
                $found['field'] = $field;
                return $found;
            }
        }
        return false;
    }
}