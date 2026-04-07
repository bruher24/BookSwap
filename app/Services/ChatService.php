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
            return Chat::all();
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

            Cache::forget('chats_' . $chat->first_user_id);
            Cache::forget('chats_' . $chat->second_user_id);

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

            Cache::forget('chats_' . $chat->first_user_id);
            Cache::forget('chats_' . $chat->second_user_id);

            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function byUser(User $user): Collection
    {
        try {
            $chats = Chat::where('first_user_id', $user->id)->orWhere('second_user_id', $user->id)->get();

            if ($chats->isEmpty()) {
                throw new Exception('Ошибка при получении чатов');
            }

            return Cache::remember('chats_' . $user->id, 600, function () use ($user, $chats): Collection {
                Log::debug('Stored in cache: ' . 'chats_' . $user->id);
                return $chats;
            });

        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function messages(Chat $chat): Collection
    {
        try {
            $messages = $chat->messages()->orderBy('created_at')->orderBy('id')->get();
            $sortedMessages = $messages->groupBy(function (Message $item) {
                return mb_substr($item->created_at, 0, 10);
            });

            return Cache::remember('messages_' . $chat->id, 600, function () use ($chat, $sortedMessages): Collection {
                Log::debug('Stored in cache: ' . 'messages_' . $chat->id);
                return $sortedMessages;
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

            if (!$message instanceof Message) {
                throw new Exception('Ошибка при сохранении сообщения');
            }

            DB::commit();

            Cache::forget('messages_' . $chat->id);

            return $message;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }
}
