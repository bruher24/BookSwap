<?php

namespace App\Services;

use App\Interfaces\BookTypeServiceInterface;
use App\Models\BookType;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class BookTypeService implements BookTypeServiceInterface
{
    private array $ucFirstFields = [
        'name',
    ];

    #[Override]
    public function create(array $data): BookType|false
    {
        $formattedData = $this->formatData($data);

        try {
            return DB::transaction(function () use ($formattedData) {
                $bookType = BookType::create($formattedData);
                return $bookType->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): BookType|false
    {
        try {
            return BookType::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(BookType::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . BookType::CACHE_KEY);
                return BookType::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function update(BookType $bookType, array $data): BookType|false
    {
        try {
            $bookType->updateOrFail($data);
            return $bookType->refresh();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function delete(BookType $bookType): bool
    {
        try {
            return $bookType->deleteOrFail();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    private function formatData(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (in_array($key, $this->ucFirstFields)) {
                $value = ucfirst($value);
            }
        }
        return $data;
    }
}
