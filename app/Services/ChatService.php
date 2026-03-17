<?php

namespace App\Services;

use App\Interfaces\ChatServiceInterface;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class ChatService implements ChatServiceInterface
{
    public function create(array $data): Chat|false
    {
        try {
            DB::beginTransaction();
            $chat = new Chat($data);

            if (!$chat->save()) {
                throw new Exception("Ошибка при создании чата");
            }

            DB::commit();
            return $chat->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function get(string $id): Chat|false
    {
        try {
            return Chat::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getAll(): Collection
    {
        try {
            return Cache::remember(Chat::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . Chat::CACHE_KEY);
                return Chat::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function where(string $field, string $value): Collection
    {
        try {
            return Chat::where($field, $value)->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function update(Chat $chat, array $data): Chat|false
    {
        try {
            DB::beginTransaction();
            $chat->updateOrFail($data);
            DB::commit();
            return $chat->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function delete(Chat $chat): bool
    {
        try {
            DB::beginTransaction();
            $chat->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function byUser(User $user): Chat|false
    {
        try {
            $chat = Chat::where('first_user_id', $user->id)->orWhere('second_user_id', $user->id)->first();

            if (!$chat) {
                throw new Exception('Ошибка при получении чата');
            }

            return $chat;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function messages(Chat $chat): Collection
    {
        try {
            $messages = $chat->messages()->orderBy('created_at')->orderBy('id')->get();
            return $messages->groupBy(function (Message $item) {
                return mb_substr($item->created_at, 0, 10);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function sendMessage(Chat $chat, User $sender, string $body): Message|false
    {
        try {
            DB::beginTransaction();

            $data = [
                'sender_id' => $sender->id,
                'body' => $body,
            ];

            $message = $chat->messages()->create($data);

            if (!$message) {
                throw new Exception('Ошибка при сохранении сообщения');
            }

            DB::commit();
            return $message;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }
}
