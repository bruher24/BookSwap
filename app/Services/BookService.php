<?php

namespace App\Services;

use App\Repositories\BookRepository;
use Illuminate\Database\Eloquent\Collection;

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

    public function create(array $data): bool
    {

        return parent::create($data);
    }
}