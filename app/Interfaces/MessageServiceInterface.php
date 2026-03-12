<?php

namespace App\Interfaces;

use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Override;

interface MessageServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Message|false;

    #[Override]
    public function get(string $id): Message|false;

    #[Override]
    public function update(string $id, array $data): Message|false;
}
