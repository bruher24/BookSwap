<?php

namespace App\Services;

use App\Interfaces\SettingServiceInterface;
use App\Models\Setting;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class SettingService implements SettingServiceInterface
{
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

    public function get(string $id): Setting|false
    {
        try {
            return Setting::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

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

    public function update(Setting $setting, array $data): Setting|false
    {
        try {
            DB::beginTransaction();
            $setting->updateOrFail($data);
            DB::commit();
            return $setting->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function delete(Setting $setting): bool
    {
        try {
            DB::beginTransaction();
            $setting->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
