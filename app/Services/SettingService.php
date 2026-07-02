<?php

namespace App\Services;

use App\Interfaces\SettingServiceInterface;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class SettingService implements SettingServiceInterface
{
    #[Override]
    public function create(array $data): Setting|false
    {
        try {
            DB::beginTransaction();
            $setting = new Setting($data);

            if (!$setting->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            DB::commit();
            return $setting->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): Setting|false
    {
        try {
            return Setting::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(Setting::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Setting::CACHE_KEY);

                return Setting::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return new Collection();
        }
    }

    #[Override]
    public function where(string $field, string $value): Collection
    {
        try {
            return Setting::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return new Collection();
        }
    }

    #[Override]
    public function update(Setting $setting, array $data): Setting|false
    {
        try {
            $setting->updateOrFail($data);

            return $setting->refresh();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return false;
        }
    }

    #[Override]
    public function delete(Setting $setting): bool
    {
        try {
            $setting->deleteOrFail();

            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return false;
        }
    }

    public function byUser(User $user): Collection
    {
        return $user->settings()
            ->withoutTrashed()
            ->get();
    }

    public function updateForUser(Setting $setting, User $user, string $value): bool
    {
        try {
            $user->settings()->syncWithoutDetachingOrFail([$setting->id => ['value' => $value]]);

            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return false;
        }
    }
}
