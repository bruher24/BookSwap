<?php

namespace App\Interfaces;

use App\Models\Setting;
use App\Models\User;

interface UserSettingServiceInterface
{
    public function updateSetting(User $user, Setting $setting, string $value): bool;
}
