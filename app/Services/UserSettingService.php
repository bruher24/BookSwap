<?php

namespace App\Services;

use App\Interfaces\SettingServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserSettingServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserSettingService implements UserSettingServiceInterface
{
    public function __construct(
        private UserServiceInterface $userService,
        private SettingServiceInterface $settingService
    ) {
    }

    public function updateSettings(string $user_id, array $data): bool
    {
        DB::beginTransaction();
        try {
            $user = $this->userService->get($user_id);
            foreach ($data as $setting_id => $value) {
                $userSettings = $user->settings();
                $settingSet = $userSettings->where('setting_id', $setting_id)->exists();
                if ($settingSet) {
                    $userSettings->updateExistingPivot($setting_id, [
                        'value' => $value,
                    ]);
                } else {
                    $userSettings->attach($setting_id, ['value' => $value]);
                }
            }
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function validateSettingsData(array $data): bool
    {
        foreach ($data as $setting_id => $value) {
            $setting = $this->settingService->get($setting_id);
            if (!$setting) {
                return false;
            }
            if (!in_array($value, $setting->available_values)) {
                return false;
            }
        }
        return true;
    }
}
