<?php

namespace App\Services;

use App\Interfaces\AuthorServiceInterface;
use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthorService extends Service implements AuthorServiceInterface
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

    public function getAll(): Collection
    {
        try {
            return Cache::remember(Author::class, 600, function () {
                Log::debug('Stored in cache: ' . Author::class);
                return Author::whereHas('books')->get();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }
}
