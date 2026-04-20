<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

final class ChatPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->isAdmin()
            ? true
            : null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chat): Response
    {
        return $chat->isUserBelongs($user)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, array $data): Response
    {
        $chatUsers = [
            $data['first_user_id'],
            $data['second_user_id'],
        ];

        return Auth::check() && in_array($user->id, $chatUsers)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chat $chat): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chat $chat): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chat $chat): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chat $chat): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    public function messages(User $user, Chat $chat): Response
    {
        return $chat->isUserBelongs($user)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    public function send(User $user, Chat $chat, int $senderId): Response
    {
        return $chat->isUserBelongs($user) && $user->id === $senderId
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }
}
