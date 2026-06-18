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

    private function formatData(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (in_array($key, $this->ucFirstFields)) {
                $value = ucfirst($value);
            }
        }
        return $data;
    }

    #[Override]
    public function create(array $data): BookType|false
    {
        $formattedData = $this->formatData($data);

        try {
            DB::beginTransaction();
            $bookType = new BookType($formattedData);

            if (!$bookType->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            DB::commit();
            return $bookType->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(string $id): BookType|false
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
    public function where(string $field, string $value): Collection
    {
        try {
            return BookType::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function update(BookType $bookType, array $data): BookType|false
    {
        try {
            DB::beginTransaction();
            $bookType->updateOrFail($data);
            DB::commit();
            return $bookType->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function delete(BookType $bookType): bool
    {
        try {
            DB::beginTransaction();
            $bookType->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
