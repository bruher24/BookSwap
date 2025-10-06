<?php

namespace App\Services;

use App\Interfaces\PhotoServiceInterface;
use App\Models\Photo;
use Override;

class PhotoService extends Service implements PhotoServiceInterface
{
    public function __construct()
    {
        parent::__construct(Photo::class);
    }

    #[Override]
    public function create(array $data): Photo|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): Photo|false
    {
        return parent::get($id);
    }
}
