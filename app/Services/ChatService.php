<?php

namespace App\Services;

use App\Interfaces\ChatServiceInterface;
use App\Interfaces\UserServiceInterface;
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
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    public function create(array $data): Chat|false
    {
        try {
            DB::beginTransaction();

            $firstUser = $this->userService->get($data['first_user_id']);
            $secondUser = $this->userService->get($data['second_user_id']);

            if (!$firstUser || !$secondUser) {
                throw new Exception('Ошибка при получении пользователей');
            }

            $pairKey = min([$firstUser->id, $secondUser->id]) . ':' . max([$firstUser->id, $secondUser->id]);

            if ($this->where('pair_key', $pairKey)->isNotEmpty()) {
                throw new Exception('Чат между этими пользователями уже существует');
            }

            $chat = new Chat(['pair_key' => $pairKey]);

            if (!$chat->save()) {
                throw new Exception("Ошибка при создании чата");
            }

            $chat->users()->attach([$firstUser->id, $secondUser->id]);
            DB::commit();
            $this->invalidateUserChatsCache($chat);
            return $chat->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function get(string $id): Chat|false
    {
        try {
            return Chat::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function where(string $field, string $value): Collection
    {
        try {
            return Chat::where($field, $value)->withoutTrashed()->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    public function delete(Chat $chat): bool
    {
        try {
            DB::beginTransaction();
            $chat->delete();
            DB::commit();
            $this->invalidateUserChatsCache($chat);
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function byUser(User $user): Collection
    {
        try {
            $chats = $user->chats()
                ->withoutTrashed()
                ->get();

            return Cache::remember('chats_' . $user->id, 600, function () use ($user, $chats): Collection {
                Log::debug('Stored in cache: ' . 'chats_' . $user->id);
                return $chats;
            });

        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    public function messages(Chat $chat): Collection
    {
        try {
            return Cache::remember('messages_' . $chat->id, 600, function () use ($chat): Collection {
                Log::debug('Stored in cache: ' . 'messages_' . $chat->id);
                return $chat->messages()
                    ->orderBy('created_at')
                    ->orderBy('id')
                    ->get();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
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

    public function block(Chat $chat, User $user): bool
    {
        try {
            DB::beginTransaction();

            if (!$chat->users()->updateExistingPivot($user->id, ['has_blocked_the_chat' => true])) {
                throw new Exception('Ошибка при обновлении чата');
            }

            DB::commit();
            $this->invalidateUserChatsCache($chat);
            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            DB::rollBack();
            return false;
        }
    }

    private function invalidateUserChatsCache(Chat $chat): void
    {
        foreach ($chat->users()->pluck('id') as $userId) {
            Cache::forget('chats_' . $userId);
        }
    }
}
