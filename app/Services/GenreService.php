<?php

namespace App\Services;

use App\Interfaces\GenreServiceInterface;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenreService extends Service implements GenreServiceInterface
{
    public function __construct()
    {
        parent::__construct(Genre::class);
        $this->ucFirstFields = [
            'name',
        ];
    }

    public function create(array $data): Genre|false
    {
        return parent::create($data);
    }

    public function get(int $id): Genre|false
    {
        return parent::get($id);
    }

    public function getAll(): Collection
    {
        try {
            return Cache::remember(Genre::class, 600, function () {
                Log::debug('Stored in cache: ' . Genre::class);
                return Genre::whereHas('books')->get();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }
}
