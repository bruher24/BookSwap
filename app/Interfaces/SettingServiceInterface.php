<?php

namespace App\Interfaces;

use App\Models\Setting;
use Override;

interface SettingServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Setting|false;

    #[Override]
    public function get(string $id): Setting|false;
}
