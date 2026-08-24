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
use Override;
use Throwable;

final class ChatService implements ChatServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    #[Override]
    public function create(array $data): Chat|false
    {
        try {
            return DB::transaction(function () use ($data) {
                $firstUser = $this->userService->get($data['first_user_id']);
                $secondUser = $this->userService->get($data['second_user_id']);

                if (empty($firstUser) || empty($secondUser)) {
                    throw new Exception('Ошибка при получении пользователей');
                }

                $pairKey = min([$firstUser->id, $secondUser->id]) . ':' . max([$firstUser->id, $secondUser->id]);

                if ($this->byPairKey($pairKey)->isNotEmpty()) {
                    throw new Exception('Чат между пользователями уже существует');
                }

                $chat = Chat::create(['pair_key' => $pairKey]);

                $chat->users()->attachOrFail([$firstUser->id, $secondUser->id]);
                $this->invalidateUserChatsCache($chat);
                return $chat->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): Chat|false
    {
        try {
            return Chat::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
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
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function delete(Chat $chat): bool
    {
        try {
            $deleted = $chat->deleteOrFail();
            $this->invalidateUserChatsCache($chat);
            return $deleted;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function byUser(User $user): Collection
    {
        $chats = $user->chats()
            ->withoutTrashed()
            ->get();

        return Cache::remember('chats_' . $user->id, 600, function () use ($user, $chats): Collection {
            Log::debug('Stored in cache: ' . 'chats_' . $user->id);
            return $chats;
        });
    }

    #[Override]
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

    #[Override]
    public function sendMessage(Chat $chat, User $sender, string $body): Message|false
    {
        try {
            return DB::transaction(function () use ($chat, $sender, $body) {
                $data = [
                    'sender_id' => $sender->id,
                    'body' => $body,
                ];

                $message = $chat->messages()->create($data);

                if (!$message instanceof Message) {
                    throw new Exception('Ошибка при сохранении сообщения');
                }

                Cache::forget('messages_' . $chat->id);
                return $message->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function block(Chat $chat, User $user): bool
    {
        try {
            return DB::transaction(function () use ($chat, $user) {
                $updated = $chat->users()->updateExistingPivotOrFail($user->id, ['has_blocked_the_chat' => true]);
                $this->invalidateUserChatsCache($chat);
                return $updated;
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    private function byPairKey(string $pairKey): Collection
    {
        return Chat::where('pair_key', $pairKey)
            ->withoutTrashed()
            ->get();
    }

    private function invalidateUserChatsCache(Chat $chat): void
    {
        foreach ($chat->users()->pluck('id') as $userId) {
            Cache::forget('chats_' . $userId);
        }
    }
}
