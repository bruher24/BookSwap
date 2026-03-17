<?php

namespace App\Interfaces;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Override;

interface SettingServiceInterface
{
    public function create(array $data): Setting|false;

    public function get(string $id): Setting|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Setting $setting, array $data): Setting|false;

    public function delete(Setting $setting): bool;
}
