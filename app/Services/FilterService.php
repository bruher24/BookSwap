<?php

namespace App\Services;

use App\Interfaces\FilterServiceInterface;
use App\Models\Filter;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class FilterService implements FilterServiceInterface
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
    public function create(array $data): Filter|false
    {
        $formattedData = $this->formatData($data);

        try {
            DB::beginTransaction();
            $filter = new Filter($formattedData);

            if (!$filter->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            DB::commit();
            return $filter->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(string $id): Filter|false
    {
        try {
            return Filter::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(Filter::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Filter::CACHE_KEY);
                return Filter::all();
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
            return Filter::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function update(Filter $filter, array $data): Filter|false
    {
        try {
            DB::beginTransaction();
            $filter->updateOrFail($data);
            DB::commit();
            return $filter->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function delete(Filter $filter): bool
    {
        try {
            DB::beginTransaction();
            $filter->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
