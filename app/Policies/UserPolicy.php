<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UserPolicy
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
    public function view(User $user, User $target): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $target): Response
    {
        return $user->is($target)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $target): Response
    {
        return $user->is($target)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $target): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $target): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    public function favorites(User $user, User $target): Response
    {
        return $user->is($target)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    public function notifications(User $user, User $target): Response
    {
        return $user->is($target)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    public function settings(User $user, User $target): Response
    {
        return $user->is($target)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }
}
