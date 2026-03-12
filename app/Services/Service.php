<?php

namespace App\Services;

use App\Interfaces\ServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

abstract class Service implements ServiceInterface
{
    protected array $ucFirstFields = [];

    /**
     * @param class-string $modelClass
     */
    public function __construct(
        protected string $modelClass
    ) {
    }

    #[Override]
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

    #[Override]
    public function get(string $id): Model|false
    {
        try {
            return $this->modelClass::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember($this->modelClass::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . $this->modelClass::CACHE_KEY);
                return $this->modelClass::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    #[Override]
    public function where(string $field, string $value): Collection
    {
        try {
            return $this->modelClass::where($field, $value)->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    #[Override]
    public function update(string $id, array $data): Model|false
    {
        try {
            DB::beginTransaction();
            $object = $this->get($id);

            if (!$object instanceof Model) {
                throw new Exception('Ошибка получения модели');
            }

            $object->updateOrFail($data);
            DB::commit();
            $object->refresh();
            return $object;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    // TODO: исправить удаление для всех моделей - сделать его идемподентным
    #[Override]
    public function delete(string $id): bool
    {
        DB::beginTransaction();
        try {
            $object = $this->get($id);

            if (!$object instanceof Model) {
                return true;
            }

            $object->delete();

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
