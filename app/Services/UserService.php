<?php

namespace App\Services;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Interfaces\UserServiceInterface;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class UserService extends Service implements UserServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }

    #[Override]
    public function create(array $data): User|false
    {
        DB::beginTransaction();
        try {
            $user = parent::create($data);

            if (!$user instanceof User) {
                throw new Exception('Ошибка при создании пользователя');
            }
            DB::commit();
            UserCreated::dispatch($user);
            return $user->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    #[Override]
    public function get(string $id): User|false
    {
        return parent::get($id);
    }

    #[Override]
    public function update(string $id, array $data): User|false
    {
        DB::beginTransaction();
        try {
            $user = $this->get($id);
            if (!$user instanceof User) {
                throw new Exception('Пользователь не найден');
            }

            if (isset($data['phone_number'])) {
                $phoneNumber = str_replace(' ', '', $data['phone_number']);
                if ($user->phone()->exists()) {
                    $user->phone()->update(['number' => $phoneNumber]);
                } else {
                    $user->phone()->create(['number' => $phoneNumber]);
                }
            }

            unset($data['phone_number']);

            if (isset($data['password'])) {
                if (!Hash::check($data['old_password'], $user->getAuthPassword())) {
                    throw new Exception('Старый пароль указан неверно');
                }

                if ($data['password'] == null) {
                    unset($data['password']);
                }
            }

            if (!parent::update($id, $data)) {
                throw new Exception('Ошибка при обновлении пользователя');
            }

            DB::commit();
            $user->refresh();
            UserUpdated::dispatch($user);
            return $user;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    #[Override]
    public function delete(string $id): bool
    {
        $user = $this->get($id);

        if ($user instanceof User) {
            UserDeleted::dispatch($user);
        }

        return parent::delete($id);
    }

    #[Override]
    public function chats(string $user_id): Collection
    {
        try {
            $user = $this->get($user_id);
            if (!$user) {
                throw new Exception();
            }

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


    #[Override]
    public function getUnreadMessages(string $user_id): Collection|false
    {
        try {
            $user = $this->get($user_id);
            if (!$user) {
                throw new Exception();
            }

            $messages = $user->unreadMessages()->distinct()->get(['id', 'from_id']);
            return $messages->groupBy('chat_id');
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    #[Override]
    public function readMessages(string $user_id, array $messagesToRead): bool
    {
        DB::beginTransaction();
        try {
            $user = $this->get($user_id);
            if (!$user) {
                throw new Exception();
            }

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
