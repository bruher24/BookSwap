<?php

namespace App\Services;

use App\Interfaces\MessageServiceInterface;
use App\Models\Message;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class MessageService implements MessageServiceInterface
{
    #[Override]
    public function create(array $data): Message|false
    {
        try {
            DB::beginTransaction();
            $message = new Message($data);

            if (!$message->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            DB::commit();
            return $message->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(string $id): Message|false
    {
        try {
            return Message::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(Message::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Message::CACHE_KEY);
                return Message::all();
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
            return Message::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function update(Message $message, array $data): Message|false
    {
        try {
            DB::beginTransaction();
            $message->updateOrFail($data);
            DB::commit();
            return $message->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function delete(Message $message): bool
    {
        try {
            DB::beginTransaction();
            $message->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
