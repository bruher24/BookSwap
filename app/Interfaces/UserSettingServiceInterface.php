<?php

namespace App\Interfaces;

interface UserSettingServiceInterface
{
    public function updateSettings(string $user_id, array $data): bool;

    public function validateSettingsData(array $data): bool;

}
