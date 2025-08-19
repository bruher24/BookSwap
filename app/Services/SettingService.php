<?php

namespace App\Services;

use App\Interfaces\SettingServiceInterface;
use App\Models\Setting;

class SettingService extends Service implements SettingServiceInterface
{
    public function __construct()
    {
        parent::__construct(Setting::class);
    }

    public function create(array $data): Setting|false
    {
        return parent::create($data);
    }

    public function get(int $id): Setting|false
    {
        return parent::get($id);
    }
}
