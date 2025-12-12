<?php

namespace App\Services;

use App\Interfaces\TradeOfferServiceInterface;
use App\Models\TradeOffer;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class TradeOfferService extends Service implements TradeOfferServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct()
    {
        parent::__construct(TradeOffer::class);
    }

    #[Override]
    public function create(array $data): TradeOffer|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): TradeOffer|false
    {
        return parent::get($id);
    }

    #[Override]
    public function bySender(string $senderId): Collection
    {
        return $this->where('sender_id', $senderId);
    }

    #[Override]
    public function byReceiver(string $receiverId): Collection
    {
        return $this->where('receiver_id', $receiverId);
    }

    #[Override]
    public function accept(string $id): bool
    {
        try {
            $tradeOffer = $this->get($id);

            if (!$tradeOffer) {
                throw new Exception('Предложение не найдено');
            }

            $tradeOffer->accept();
            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    #[Override]
    public function reject(string $id): bool
    {
        try {
            $tradeOffer = $this->get($id);

            if (!$tradeOffer) {
                throw new Exception('Предложение не найдено');
            }

            $tradeOffer->reject();
            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
