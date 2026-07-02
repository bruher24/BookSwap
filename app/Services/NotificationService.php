<?php

namespace App\Services;

use App\Interfaces\NotificationServiceInterface;
use App\Models\Notification;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class NotificationService implements NotificationServiceInterface
{
    #[Override]
    public function create(array $data): Notification|false
    {
        try {
            DB::beginTransaction();
            $notification = new Notification($data);

            if (!$notification->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            DB::commit();
            return $notification->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): Notification|false
    {
        try {
            return Notification::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(Notification::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Notification::CACHE_KEY);
                return Notification::all();
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
            return Notification::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function update(Notification $notification, array $data): Notification|false
    {
        try {
            DB::beginTransaction();
            $notification->updateOrFail($data);
            DB::commit();
            return $notification->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function delete(Notification $notification): bool
    {
        try {
            DB::beginTransaction();
            $notification->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function read(Notification $notification): bool
    {
        try {
            DB::beginTransaction();

            if (!$notification->updateOrFail(['seen' => true])) {
                throw new Exception('Ошибка при прочтении уведомления');
            }

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function byUser(User $user): Collection
    {
        return $user->notifications()
            ->withoutTrashed()
            ->get();
    }

    #[Override]
    public function readAll(User $user): bool
    {
        return !!Notification::where('user_id', $user->id)->update(['seen' => true]);
    }
}
