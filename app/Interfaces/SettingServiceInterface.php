<?php

namespace App\Interfaces;

use App\Models\Setting;

interface SettingServiceInterface extends ServiceInterface
{
    public function create(array $data): Setting|false;

    public function get(string $id): Setting|false;
}
