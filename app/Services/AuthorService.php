<?php

namespace App\Services;

use App\Events\AuthorCreated;
use App\Events\AuthorDeleted;
use App\Events\AuthorUpdated;
use App\Interfaces\AuthorServiceInterface;
use App\Models\Author;
use Override;

final class AuthorService extends Service implements AuthorServiceInterface
{
    public function __construct()
    {
        parent::__construct(Author::class);
        $this->ucFirstFields = [
            'lastname',
            'firstname',
            'patronymic',
        ];
    }

    #[Override]
    public function create(array $data): Author|false
    {
        $author = parent::create($data);

        if ($author instanceof Author) {
            AuthorCreated::dispatch($author);
        }

        return $author;
    }

    #[Override]
    public function get(string $id): Author|false
    {
        return parent::get($id);
    }

    public function update(string $id, array $data): bool
    {
        $author = $this->get($id);
        $updated = parent::update($id, $data);

        if ($author instanceof Author && $updated) {
            AuthorUpdated::dispatch($author);
        }

        return $updated;
    }

    public function delete(string $id): bool
    {
        $author = $this->get($id);

        if ($author instanceof Author) {
            AuthorDeleted::dispatch($author);
        }

        return parent::delete($id);
    }
}
