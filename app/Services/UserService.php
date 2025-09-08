<?php

namespace App\Services;

use App\Events\MessageSent;
use App\Interfaces\UserServiceInterface;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Photo;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserService extends Service implements UserServiceInterface
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function create(array $data): User|false
    {
        DB::beginTransaction();
        try {
            $user = parent::create($data);
            if ($user instanceof User) {
                $user->refresh();
                $user->roles()->attach(2);
                $user->photo()->associate(Photo::all()->first());
                // TODO: изменить
                $user->settings()->attach(Setting::all()->first(), ['value' => 'on']);
                $user->save();
                DB::commit();
                return $user;
            }
            return false;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function get(int $id): User|false
    {
        return parent::get($id);
    }

    public function update(string $id, array $data): bool
    {
        DB::beginTransaction();
        try {
            $object = $this->get($id);
            if (!$object instanceof User) {
                return false;
            }

            $user = $object;

            if (isset($data['phone_number'])) {
                $phoneNumber = str_replace(' ', '', $data['phone_number']);
                if ($user->phone()->exists()) {
                    $user->phone()->update(['number' => $phoneNumber]);
                } else {
                    $user->phone()->create(['number' => $phoneNumber]);
                }
            }

            unset($data['phone_number']);

            if (isset($data['password']) && !Hash::check($data['old_password'], $user->getAuthPassword())) {
                return false;
            }

            if ($data['password'] == null) {
                unset($data['password']);
            }

            if (!parent::update($user, $data)) {
                return false;
            }

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getChat(string $user_id, string $recipient_id): Chat|false
    {
        $arr = [$user->id, $recipient->id];
        sort($arr);
        return Chat::where('first_user_id', $arr[0])->where('second_user_id', $arr[1])->first();
    }

    public function groupMessages(Collection $messages): Collection
    {
        try {
            return $messages->groupBy(function (Message $item, int $key) {
                return mb_substr($item->created_at, 0, 10);
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function chats(string $user_id): Collection
    {
        try {
            Log::debug('Cache check');
            return Cache::remember($user->id . '_chats', 60, function () use ($user) {
                Log::debug('Stored in cache: ' . $user->id . '_chats');
                return $user->chats()->get();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function sendMessage(string $user_id, string $recipient_id, string $body): Message|false
    {
        DB::beginTransaction();
        try {
            $chat = $this->getChat($user, $recipient);

            $message = new Message([
                'chat_id' => $chat->id,
                'from_id' => $user->id,
                'to_id' => $recipient->id,
                'body' => $body,
            ]);

            $saved = $chat->messages()->save($message);

            if (!$saved) {
                throw new Exception('Ошибка при сохранении сообщения');
            }

            DB::commit();

            $message->refresh();

            if ($message->to_id != $message->from_id) {
                Event::dispatch(new MessageSent($message));
            }

            return $message;
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return false;
        }
    }

    public function getUserNotifications(string $user_id): Collection
    {
        return $user->notifications()->get();
    }

    public function checkOneNotification(string $user_id, int $notificationId): bool
    {
        DB::beginTransaction();
        try {
            $notification = $user->notifications()->findOrFail($notificationId);

            $notification->updateOrFail(['seen' => true]);

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function checkManyNotifications(string $user_id, array $notificationIds): bool
    {
        $notifications = $user->notifications()->whereIn('id', $notificationIds)->get();
        return $this->updateNotifications($notifications);
    }

    public function updateNotifications(Collection $notifications): bool
    {
        DB::beginTransaction();
        try {
            $notifications->each(function (Notification $notification) {
                $notification->updateOrFail(['seen' => true]);
            });

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getUnreadMessages(string $user_id): Collection
    {
        return $user->unreadMessages()->distinct()->get(['id', 'from_id']);
    }

    public function readMessages(string $user_id, array $messagesToRead): bool
    {
        DB::beginTransaction();
        try {
            $messages = $user->unreadMessages()->whereIn('id', $messagesToRead)->get();

            $messages->each(function (Message $message) {
                $message->updateOrFail(['seen' => true]);
            });

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
