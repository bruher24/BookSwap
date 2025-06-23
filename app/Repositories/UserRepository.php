<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements RepositoryInterface
{

    public function create(array $data)
    {
        $user = new User($data);
        if (!$user->save()) {
            throw new \Exception("Ошибка при сохранении пользователя");
        }
        $user->refresh();
        return $user;
    }

    public function update(int $id, array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id)
    {
        User::destroy($id);
    }

    public function all(): Collection
    {
        return User::all();
    }

    public function get($id)
    {
        $user = User::find($id);
        if (!isset($user)) {
            throw new ModelNotFoundException("Пользователь с данным ID не найден");
        }
        return $user;
    }
}