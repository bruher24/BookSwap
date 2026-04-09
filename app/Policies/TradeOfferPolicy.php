<?php

namespace App\Policies;

use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

final class TradeOfferPolicy
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
    public function view(User $user, TradeOffer $tradeOffer): Response
    {
        return $tradeOffer->isUserBelongs($user)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
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
    public function update(User $user, TradeOffer $tradeOffer): Response
    {
        return $tradeOffer->isUserBelongs($user)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TradeOffer $tradeOffer): Response
    {
        return $tradeOffer->isUserBelongs($user)
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TradeOffer $tradeOffer): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TradeOffer $tradeOffer): Response
    {
        return $user->isAdmin()
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    public function answer(User $user, TradeOffer $tradeOffer): Response
    {
        return $user->id === $tradeOffer->receiver_id
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    public function bySender(User $user, User $sender): Response
    {
        return $user->id === $sender->id
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }

    public function byReceiver(User $user, User $receiver): Response
    {
        return $user->id === $receiver->id
            ? Response::allow()
            : Response::deny('Недостаточно прав');
    }
}
