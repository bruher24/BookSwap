<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function create(array $data): bool
    {
        try {
            $this->userRepository->create($data);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function update(int $id, array $data): bool
    {
        try {
            $this->userRepository->update($id, $data);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function delete(int $id): bool
    {
        try {
            $this->userRepository->delete($id);
        } catch (\Exception $e) {
            logger($e->getMessage());
            return false;
        }
        return true;
    }

    public function getAll(): Collection
    {
        return $this->userRepository->getAll();
    }

    public function get(int $id): User | false
    {
        try {
            $user = $this->userRepository->get($id);
        } catch (ModelNotFoundException $e) {
            logger($e->getMessage());
            return false;
        }
        return $user;
    }
}