<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

final class AuthorService extends Service
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

    public function params($books): array
    {
        $params = parent::params($books);
        unset($params['authors']);
        return $params;
    }
}
