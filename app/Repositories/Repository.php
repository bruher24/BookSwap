<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Repository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data): Model
    {
        $object = new $this->model($data);
        if (!$object->save()) {
            throw new Exception("Ошибка при сохранении записи");
        }
        $object->refresh();
        return $object;
    }

    public function update(int $id, array $data): Model
    {
        $object = $this->model::find($id);
        if (!$object->update($data)) {
            throw new Exception('Ошибка при обновлении записи');
        }
        $object->refresh();
        return $object;
    }

    public function delete(int $id): void
    {
        $object = $this->model::find($id);
        if (!$object->delete()) {
            throw new Exception('Ошибка при удалении записи');
        }
    }

    public function getAll(): Collection
    {
        return $this->model::all();
    }

    public function get($id)
    {
        $object = $this->model::find($id);
        if (!isset($object)) {
            throw new ModelNotFoundException("Запись с данным ID не найден");
        }
        return $object;
    }
}