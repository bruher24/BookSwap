<?php

namespace App\Services;

use App\Interfaces\ServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

abstract class Service implements ServiceInterface
{
    protected array $ucFirstFields = [];

    public function __construct(protected string $modelClass)
    {
    }

    public function create(array $data): Model|false
    {
        $formattedData = $this->formatData($data);
        try {
            $object = new $this->modelClass($formattedData);
            if (!$object->save()) {
                throw new Exception("Ошибка при сохранении записи");
            }
            $object->refresh();
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return $object;
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
            $object = $this->modelClass::find($id);
            if (!$object) {
                throw new ModelNotFoundException("Запись с ID: [$id] не найдена.");
            }
        } catch (ModelNotFoundException $e) {
            logger($e->getMessage());
            return false;
        }
        return $object;
    }

    public function getMany(array $ids): Collection
    {
        try {
            $collection = $this->modelClass::whereIn('id', $ids)->get();
            if (!$collection) {
                throw new ModelNotFoundException("Записей с ID: [" . implode(', ', $ids) . "] не найдено.");
            }
        } catch (ModelNotFoundException $e) {
            logger($e->getMessage());
            return new Collection();
        }
        return $collection;
    }

    public function getAll(): Collection
    {
        return $this->modelClass::all();
    }

    public function update(Model $object, array $data): bool
    {
        try {
            if (!$object->update($data)) {
                throw new Exception("Ошибка при обновлении записи ID: [$object->id].");
            }
            $object->refresh();
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function delete(Model $object): bool
    {
        try {
            if (!$object->delete()) {
                throw new Exception("Ошибка при удалении записи ID: [$object->id].");
            }
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function params(Collection $books = null): array
    {
        $books = $books ?? $this->getAll();
        $result['genres'] = $books->flatMap->genres->unique('name');
        $result['authors'] = $books->flatMap->authors->unique(function ($author) {
            return implode('|', [
                $author->lastname,
                $author->firstname,
                $author->patronymic ?? ''
            ]);
        });
        $result['years'] = $books->pluck('publication_year')->unique();
        $result['book_types'] = $books->pluck('book_type')->filter()->unique();
        return $result ?? [];
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
