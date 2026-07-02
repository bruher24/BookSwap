<?php

namespace App\Interfaces;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface SettingServiceInterface
{
    public function create(array $data): Setting|false;

    public function get(int $id): Setting|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Setting $setting, array $data): Setting|false;

    public function delete(Setting $setting): bool;

    public function byUser(User $user): Collection;

    public function updateForUser(Setting $setting, User $user, string $value): bool;
}
