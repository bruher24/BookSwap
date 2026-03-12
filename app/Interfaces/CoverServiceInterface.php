<?php

namespace App\Interfaces;

use App\Models\Cover;
use Override;

interface CoverServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Cover|false;

    #[Override]
    public function get(string $id): Cover|false;

    #[Override]
    public function update(string $id, array $data): Cover|false;
}
