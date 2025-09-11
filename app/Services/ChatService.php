<?php

namespace App\Services;

use App\Interfaces\ChatServiceInterface;
use App\Models\Chat;
use App\Models\Message;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatService extends Service implements ChatServiceInterface
{
    public function __construct()
    {
        parent::__construct(Chat::class);
    }

    public function create(array $data): Chat|false
    {
        return parent::create($data);
    }

    public function get(string $id): Chat|false
    {
        return parent::get($id);
    }

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

    public function messages(string $chat_id): Collection
    {
        try {
            $chat = $this->get($chat_id);
            if (!$chat) {
                throw new Exception('Чат не найден');
            }
            $messages = $chat->messages()->orderBy('created_at')->orderBy('id')->get();
            return $messages->groupBy(function (Message $item, int $key) {
                return mb_substr($item->created_at, 0, 10);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }
}
