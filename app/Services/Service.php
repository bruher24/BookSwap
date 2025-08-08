<?php

namespace App\Services;

use App\Interfaces\ServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Service implements ServiceInterface
{
    protected array $ucFirstFields = [];

    public function __construct(protected string $modelClass)
    {
    }

    public function create(array $data): Model|false
    {
        $formattedData = $this->formatData($data);
        DB::beginTransaction();
        try {
            $object = new $this->modelClass($formattedData);
            if (!$object->save()) {
                throw new Exception("Ошибка при сохранении записи");
            }

            DB::commit();
            $object->refresh();
            return $object;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    final protected function formatData(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (in_array($key, $this->ucFirstFields)) {
                $value = ucfirst($value);
            }
        }

        return $data;
    }

    public function get(int $id): Model|false
    {
        try {
            return $this->modelClass::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getMany(array $ids): Collection
    {
        return $this->modelClass::whereIn('id', $ids)->get();
    }

    public function getAll(): Collection
    {
        try {
            return Cache::remember($this->modelClass, 600, function () {
                Log::debug('Stored in cache: ' . $this->modelClass);
                return $this->modelClass::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function update(Model $object, array $data): bool
    {
        DB::beginTransaction();
        try {
            $author = $data['author_id'];
            unset($data['author_id']);
            $object->updateOrFail($data);

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function delete(Model $object): bool
    {
        DB::beginTransaction();
        try {
            $object->deleteOrFail();

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    final public function getFilterFromRequest(Request $request): array
    {
        if ($request->isMethod('POST')) {
            $inputFilters = $request->except('_token');
            $filters = $this->formatFilters($inputFilters);
        }
        return $filters ?? [];
    }

    final protected function formatFilters(array $inputFilters): array
    {
        $filters = [];
        foreach ($inputFilters as $key => $value) {
            [$field, $id] = explode('-', $key);
            $filters[$field] = $filters[$field] ?? [];
            $filters[$field][] = $id;
        }
        return $filters;
    }
}

