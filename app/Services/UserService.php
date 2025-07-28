<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use App\Models\UsersFavoriteBooks;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService extends Service implements UserServiceInterface
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function update(Model $object, array $data): bool
    {
        if (!$object instanceof User) {
            return false;
        }

        $user = $object;

        if (isset($data['phone_number'])) {
            try {
                $phoneNumber = str_replace(' ', '', $data['phone_number']);
                if ($user->phone()->exists()) {
                    $user->phone()->update(['number' => $phoneNumber]);
                } else {
                    $user->phone()->create(['number' => $phoneNumber]);
                }
            } catch (Exception $exception) {
                logger($exception->getMessage());
                return false;
            }
        }

        unset($data['phone_number']);

        if (isset($data['password']) && !Hash::check($data['old_password'], $user->getAuthPassword())) {
            return false;
        }

        if (!parent::update($user, $data)) {
            return false;
        }

        return true;
    }

    public function updateSettings(User $user, array $data): bool
    {
        try {
            $setting = $user->settings()->where('name', $data['setting_name']);
            if ($setting->first()) {
                $id = $setting->first()->id;
                $setting->updateExistingPivot($id, ['value' => $data['setting_value'] ?? 'off']);
            } else {
                $toAttach = Setting::where('name', $data['setting_name'])->first();
                $user->settings()->attach($toAttach->id, ['value' => $data['setting_value'] ?? 'off']);
            }
        } catch (Exception $exception) {
            logger($exception->getMessage());
            return false;
        }
        return true;
    }

    public function addToFavorites($user_id, $book_id): void
    {
        UsersFavoriteBooks::withTrashed()->updateOrCreate([
            'user_id' => $user_id,
            'book_id' => $book_id,
        ])->restore();
    }

    public function removeFromFavorites($user_id, $book_id): void
    {
        $record = UsersFavoriteBooks::where('user_id', $user_id)->where('book_id', $book_id)->first();
        $record->delete();
    }

    public function getMessages(User $user, User $recipient): Collection
    {
        // TODO: если сам себе пишет, то не слать уведы
        $messages = Message::where(function ($query) use ($user, $recipient) {
            $query->where('from_id', $user->id)
                ->where('to_id', $recipient->id);
        })->orWhere(function ($query) use ($user, $recipient) {
            $query->where('from_id', $recipient->id)
                ->where('to_id', $user->id);
        })->orderBy('created_at');

        return $messages->get();
    }

    public function groupMessages(Collection $messages): \Illuminate\Support\Collection
    {
        $grouped = $messages->groupBy(function (Message $item, int $key) {
            return mb_substr($item->created_at, 0, 10);
        })->all();
        return collect($grouped);
    }

    public function getUserChats(User $user): Collection
    {
        $chats = $user->chats;
        return $chats;
    }

    public function sendMessage(User $user, User $recipient, string $body): JsonResponse
    {
        try {
            $chatQuery = Chat::where(function ($query) use ($user, $recipient) {
                $query->where('first_user_id', $user->id)
                    ->where('second_user_id', $recipient->id);
            })->orWhere(function ($query) use ($user, $recipient) {
                $query->where('first_user_id', $recipient->id)
                    ->where('second_user_id', $user->id);
            });
            $chat = $chatQuery->first();

            $message = new Message([
                'chat_id' => $chat->id,
                'from_id' => $user->id,
                'to_id' => $recipient->id,
                'body' => $body,
            ]);

            $chat->messages()->save($message);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json([
                'success' => false,
            ]);
        }

        $message->refresh();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}