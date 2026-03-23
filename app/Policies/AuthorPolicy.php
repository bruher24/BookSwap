<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

final class AuthorPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Author $author): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Auth::check()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Author $author): Response
    {
        return $user->id === $author->user_id
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Author $author): Response
    {
        return $user->id === $author->user_id
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Author $author): Response
    {
        return $user->id === $author->user_id
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Author $author): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }
}
