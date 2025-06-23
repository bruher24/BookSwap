<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements RepositoryInterface
{

    public function create(array $data): User
    {
        $user = new User($data);
        if (!$user->save()) {
            throw new \Exception("Ошибка при сохранении пользователя");
        }
        $user->refresh();
        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = User::find($id);
        if (!$user->update($data)) {
            throw new \Exception('Ошибка при обновлении пользователя');
        }
        $user->refresh();
        return $user;
    }

    public function delete(int $id): void
    {
        $user = User::find($id);
        if(!$user->delete()) {
            throw new \Exception('Ошибка при удалении пользователя');
        }
    }

    public function getAll(): Collection
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