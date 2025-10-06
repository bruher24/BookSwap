<?php

namespace App\Interfaces;

use App\Models\Author;
use Override;

interface AuthorServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Author|false;

    #[Override]
    public function get(string $id): Author|false;
}
