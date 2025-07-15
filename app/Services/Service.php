<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Service
{
    protected array $ucFirstFields = [];

    public function __construct(protected string $modelClass)
    {
    }

    public function create(array $data): Model|false
    {
        $formattedData = $this->formatData($data);
        try {
            $book = new $this->modelClass($formattedData);
            if (!$book->save()) {
                throw new Exception("Ошибка при сохранении записи");
            }

            $book->refresh();
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return $book;
    }

    protected function formatData(array $data): array
    {
        foreach ($data as $key => &$value) {
            if (in_array($key, $this->ucFirstFields)) {
                $value = ucfirst($value);
            }
        }
        return $data;
    }

    public function update(int $id, array $data): bool
    {
        try {
            $object = $this->modelClass::find($id);
            if (!$object->update($data)) {
                throw new Exception('Ошибка при обновлении записи');
            }

            $object->refresh();
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function delete(int $id): bool
    {
        try {
            $object = $this->modelClass::find($id);
            if (!$object->delete()) {
                throw new Exception('Ошибка при удалении записи');
            }
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function getAll(): Collection
    {
        return $this->modelClass::all();
    }

    public function get(int $id): Model|false
    {
        try {
            $object = $this->modelClass::find($id);
            if (!isset($object)) {
                throw new ModelNotFoundException("Запись с данным ID не найден");
            }
        } catch (ModelNotFoundException $e) {
            logger($e->getMessage());
            return false;
        }
        return $object;
    }

    public function where(array $conditions): Collection|false
    {
        return false;
    }

    public function params($books): array
    {
        $result['genres'] = $books->flatMap->genres->unique('name');
        $result['authors'] = $books->flatMap->authors->unique(function ($author) {
            return implode('|', [
                $author->lastname,
                $author->firstname,
                $author->patronymic ?? ''
            ]);
        });
        $result['years'] = $books->pluck('publication_year')->unique();
        $result['types'] = $books->pluck('type')->filter()->unique();
        return $result ?? [];
    }

    public function formatFilters(array $inputFilters): array
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
