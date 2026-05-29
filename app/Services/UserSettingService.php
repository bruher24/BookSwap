<?php

namespace App\Services;

use App\Interfaces\UserSettingServiceInterface;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class UserSettingService implements UserSettingServiceInterface
{
    public function updateSetting(User $user, Setting $setting, string $value): Setting|false
    {
        try {
            DB::beginTransaction();
            $userSettings = $user->settings();
            $settingIsSet = $userSettings->where('setting_id', $setting->id)->exists();

            if ($settingIsSet) {
                $userSettings->updateExistingPivot($setting->id, ['value' => $value]);
            } else {
                $userSettings->attach($setting->id, ['value' => $value]);
            }

            DB::commit();

            $updated = $userSettings->where('setting_id', $setting->id)->first();

            if (!$updated instanceof Setting) {
                throw new Exception('Ошибка при получении обновленных настроек');
            }

            return $updated;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
