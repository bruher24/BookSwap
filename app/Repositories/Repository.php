<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

abstract class Repository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
        DB::beginTransaction();
    }

    public function create(array $data): Model
    {
        $object = new $this->model($data);
        if (!$object->save()) {
            DB::rollBack();
            throw new Exception("Ошибка при сохранении записи");
        }
        DB::commit();
        $object->refresh();
        return $object;
    }

    public function update(int $id, array $data): Model
    {
        $object = $this->model::find($id);
        if (!$object->update($data)) {
            DB::rollBack();
            throw new Exception('Ошибка при обновлении записи');
        }
        DB::commit();
        $object->refresh();
        return $object;
    }

    public function delete(int $id): void
    {
        $object = $this->model::find($id);
        if (!$object->delete()) {
            DB::rollBack();
            throw new Exception('Ошибка при удалении записи');
        }
        DB::commit();
    }

    public function getAll(): Collection
    {
        return $this->model::all();
    }

    public function get($id)
    {
        $object = $this->model::find($id);
        if (!isset($object)) {
            DB::rollBack();
            throw new ModelNotFoundException("Запись с данным ID не найден");
        }
        DB::commit();
        return $object;
    }
}