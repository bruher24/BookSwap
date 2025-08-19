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
}
