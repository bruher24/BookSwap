<?php

namespace App\Services;

use App\Interfaces\SettingServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserSettingServiceInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class UserSettingService implements UserSettingServiceInterface
{
    public function updateSettings(User $user, array $data): bool
    {
        try {
            DB::beginTransaction();
            $userSettings = $user->settings();

            foreach ($data as $setting_id => $value) {
                $settingIsSet = $userSettings->where('setting_id', $setting_id)->exists();

                if ($settingIsSet) {
                    $userSettings->updateExistingPivot($setting_id, ['value' => $value]);
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
        $settingService = new SettingService();

        foreach ($data as $setting_id => $value) {
            $setting = $settingService->get($setting_id);

            if (!$setting || !in_array($value, $setting->available_values)) {
                return false;
            }
        }

        return true;
    }
}
