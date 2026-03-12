<?php

namespace App\Interfaces;

use App\Models\Photo;
use Override;

interface PhotoServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Photo|false;

    #[Override]
    public function get(string $id): Photo|false;

    #[Override]
    public function update(string $id, array $data): Photo|false;
}
