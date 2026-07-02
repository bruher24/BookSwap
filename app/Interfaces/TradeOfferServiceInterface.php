<?php

namespace App\Interfaces;

use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface TradeOfferServiceInterface
{
    public function create(array $data): TradeOffer|false;

    public function get(int $id): TradeOffer|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(TradeOffer $tradeOffer, array $data): TradeOffer|false;

    public function delete(TradeOffer $tradeOffer): bool;

    public function items(TradeOffer $tradeOffer): Collection;

    public function bySender(User $sender): Collection;

    public function byReceiver(User $receiver): Collection;

    public function accept(TradeOffer $tradeOffer): bool;

    public function reject(TradeOffer $tradeOffer): bool;

    public function cancel(TradeOffer $tradeOffer): bool;

    public function finish(TradeOffer $tradeOffer): bool;

    public function tradeHistory(User $user): \Illuminate\Support\Collection;
}
