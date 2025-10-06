<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Override;

interface UserServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): User|false;

    #[Override]
    public function get(string $id): User|false;

    public function chats(string $user_id): Collection;

    public function getUnreadMessages(string $user_id): Collection|false;

    public function readMessages(string $user_id, array $messagesToRead): bool;
}
