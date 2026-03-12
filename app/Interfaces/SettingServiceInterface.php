<?php

namespace App\Interfaces;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Override;

interface SettingServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Setting|false;

    #[Override]
    public function get(string $id): Setting|false;

    #[Override]
    public function update(string $id, array $data): Setting|false;
}
