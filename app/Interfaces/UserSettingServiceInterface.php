<?php

namespace App\Interfaces;

use App\Models\User;

interface UserSettingServiceInterface
{
    public function updateSettings(User $user, array $data): bool;

    public function validateSettingsData(array $data): bool;

}
