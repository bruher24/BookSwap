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

    protected array $ucFirstFields;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data): Model|false
    {
        $formattedData = $this->formatData($data);
        try {
            $book = $this->repository->create($formattedData);
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
