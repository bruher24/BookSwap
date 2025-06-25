<?php

namespace App\Services;

use App\Repositories\Repository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Service
{
    protected Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data): bool
    {
        try {
            $this->repository->create($data);
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->repository->update($id, $data);
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function delete(int $id): bool
    {
        try {
            $this->repository->delete($id);
        } catch (Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function get(int $id): Model|false
    {
        try {
            $user = $this->repository->get($id);
        } catch (ModelNotFoundException $e) {
            logger($e->getMessage());
            return false;
        }
        return $user;
    }

    public function where(array $conditions): Collection|false
    {
        if (method_exists($this->repository, 'where')) {
            return $this->repository->where($conditions);
        }
        return false;
    }
}
