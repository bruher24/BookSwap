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
    public function byUsers(string $user_id, string $recipient_id): Chat|false
    {
        try {
            $arr = [$user_id, $recipient_id];
            sort($arr);
            $chat = Chat::where('first_user_id', $arr[0])->where('second_user_id', $arr[1])->first();
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
    public function messages(string $chat_id): Collection
    {
        try {
            $chat = $this->get($chat_id);
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
    public function sendMessage(string $user_id, string $recipient_id, string $body): Message|false
    {
        DB::beginTransaction();
        try {
            $chat = $this->byUsers($user_id, $recipient_id);

            if (!$chat instanceof Chat) {
                throw new Exception('Ошибка получения чата');
            }

            $message = new Message([
                'chat_id' => $chat->id,
                'from_id' => $user_id,
                'to_id' => $recipient_id,
                'body' => $body,
            ]);

            $saved = $chat->messages()->save($message);

            if (!$saved) {
                throw new Exception('Ошибка при сохранении сообщения');
            }

            DB::commit();

            $message->refresh();

            if ($message->to_id != $message->from_id) {
                MessageSent::dispatch($message);
            }

            return $message;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }
}
