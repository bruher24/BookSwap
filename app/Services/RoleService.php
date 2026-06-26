<?php

namespace App\Services;

use App\Interfaces\RoleServiceInterface;
use App\Models\Role;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class RoleService implements RoleServiceInterface
{
    #[Override]
    public function create(array $data): Role|false
    {
        try {
            DB::beginTransaction();
            $role = new Role($data);

            if (!$role->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            DB::commit();
            return $role->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): Role|false
    {
        try {
            return Role::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(Role::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Role::CACHE_KEY);
                return Role::all();
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
            return Role::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function update(Role $role, array $data): Role|false
    {
        try {
            DB::beginTransaction();
            $role->updateOrFail($data);
            DB::commit();
            return $role->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function delete(Role $role): bool
    {
        try {
            DB::beginTransaction();
            $role->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
