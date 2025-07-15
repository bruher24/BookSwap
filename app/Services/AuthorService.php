<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

final class AuthorService extends Service
{
    public function __construct() {
        parent::__construct(Author::class);
        $this->ucFirstFields = [
            'lastname',
            'firstname',
            'patronymic',
        ];
    }

    public function where(array $conditions): Collection|false
    {
        if (isset($conditions['lastname'])) {
            $authorId = Author::where('lastname', 'like', '%' . $conditions['lastname'] . '%')->get('id');
            $conditions['author'][] = $authorId;
        }

        if (isset($conditions['firstname'])) {
            $authorId = Author::where('lastname', 'like', '%' . $conditions['lastname'] . '%')->get('id');
            $conditions['author'][] = $authorId;
        }

        if (isset($conditions['patronymic'])) {
            $authorId = Author::where('patronymic', 'like', '%' . $conditions['patronymic'] . '%')->get('id');
            $conditions['author'][] = $authorId;
        }
        return Author::whereIn('id', $conditions['author'])->get() ?? false;
    }

    public function params($books): array
    {
        $params = parent::params($books);
        unset($params['authors']);
        return $params;
    }
}
