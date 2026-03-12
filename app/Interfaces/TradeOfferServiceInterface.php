<?php

namespace App\Interfaces;

use App\Models\TradeOffer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Override;

interface TradeOfferServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): TradeOffer|false;

    #[Override]
    public function get(string $id): TradeOffer|false;

    public function bySender(string $senderId): Collection;

    public function byReceiver(string $receiverId): Collection;

    public function accept(string $id): bool;

    public function reject(string $id): bool;

    #[Override]
    public function update(string $id, array $data): TradeOffer|false;
}
