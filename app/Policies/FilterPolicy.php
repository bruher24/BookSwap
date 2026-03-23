<?php

namespace App\Policies;

use App\Models\Filter;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class FilterPolicy
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
    public function view(User $user, Filter $filter): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Filter $filter): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Filter $filter): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Filter $filter): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Filter $filter): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }
}
