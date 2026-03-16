<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Interfaces\ChatServiceInterface;
use App\Models\Chat;
use App\Models\Message;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class ChatService extends Service implements ChatServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct()
    {
        parent::__construct(Chat::class);
    }

    #[Override]
    public function create(array $data): Chat|false
    {
        try {
            $sorted = [$data['first_user_id'], $data['second_user_id']];
            sort($sorted);
            $data['first_user_id'] = $sorted[0];
            $data['second_user_id'] = $sorted[1];
            return parent::create($data);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    #[Override]
    public function get(string $id): Chat|false
    {
        return parent::get($id);
    }

    #[Override]
    public function update(string $id, array $data): Chat|false
    {
        return parent::update($id, $data);
    }

    public function byUser(string $userId): Chat|false
    {
        try {
            $chat = Chat::where('first_user_id', $userId)->orWhere('second_user_id', $userId)->first();

            if (!$chat) {
                throw new Exception('Ошибка при получении чата');
            }

            return $chat;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    #[Override]
    public function messages(string $chatId): Collection
    {
        try {
            $chat = $this->get($chatId);

            if (!$chat) {
                throw new Exception('Чат не найден');
            }

            $messages = $chat->messages()->orderBy('created_at')->orderBy('id')->get();
            return $messages->groupBy(function (Message $item) {
                return mb_substr($item->created_at, 0, 10);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    #[Override]
    public function sendMessage(string $chatId, string $senderId, string $body): Message|false
    {
        try {
            DB::beginTransaction();
            $chat = $this->get($chatId);

            if (!$chat instanceof Chat) {
                throw new Exception('Ошибка получения чата');
            }

            $message = new Message([
                'chat_id' => $chat->id,
                'sender_id' => $senderId,
                'body' => $body,
            ]);

            $saved = $chat->messages()->save($message);

            if (!$saved) {
                throw new Exception('Ошибка при сохранении сообщения');
            }

            DB::commit();
            $message->refresh();

            return $message;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }
}
