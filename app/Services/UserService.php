<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function update(array $data, int $id)
    {

    }

    public function delete(int $id)
    {

    }

    public function getAll()
    {

    }

    public function get(int $id)
    {
        try {
            $user = $this->userRepository->get($id);
        } catch (ModelNotFoundException $e) {
            logger($e->getMessage());
            return null;
        }
        return $user;
    }
}