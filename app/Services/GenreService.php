<?php

namespace App\Services;

use App\Interfaces\GenreServiceInterface;
use App\Models\Genre;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class GenreService implements GenreServiceInterface
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

    public function create(array $data): Genre|false
    {
        $formattedData = $this->formatData($data);

        try {
            DB::beginTransaction();
            $genre = new Genre($formattedData);

            if (!$genre->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            DB::commit();
            return $genre->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function get(string $id): Genre|false
    {
        try {
            return Genre::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function getAll(): Collection
    {
        try {
            return Cache::remember(Genre::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Genre::CACHE_KEY);
                return Genre::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    public function where(string $field, string $value): Collection
    {
        try {
            return Genre::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    public function update(Genre $genre, array $data): Genre|false
    {
        try {
            DB::beginTransaction();
            $genre->updateOrFail($data);
            DB::commit();
            return $genre->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function delete(Genre $genre): bool
    {
        try {
            DB::beginTransaction();
            $genre->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
