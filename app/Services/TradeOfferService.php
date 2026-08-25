<?php

namespace App\Services;

use App\Enums\TradeOfferStatusEnum;
use App\Events\TradeOfferUpdatedEvent;
use App\Interfaces\TradeOfferServiceInterface;
use App\Models\Book;
use App\Models\TradeOffer;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class TradeOfferService implements TradeOfferServiceInterface
{
    #[Override]
    public function create(array $data): TradeOffer|false
    {
        try {
            return DB::transaction(function () use ($data) {
                $data['status'] = TradeOfferStatusEnum::Pending;
                $tradeOffer = TradeOffer::create($data);
                $this->attachBooksToTradeOffer($tradeOffer, $data['sender_items'], $data['receiver_items']);
                return $tradeOffer->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function get(int $id): TradeOffer|false
    {
        try {
            return TradeOffer::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function getAll(): Collection
    {
        try {
            return Cache::remember(TradeOffer::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . TradeOffer::CACHE_KEY);
                return TradeOffer::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    #[Override]
    public function update(TradeOffer $tradeOffer, array $data): TradeOffer|false
    {
        try {
            if ($tradeOffer->status !== TradeOfferStatusEnum::Pending) {
                throw new Exception('Можно изменять только сделки со статусом "' . TradeOfferStatusEnum::Pending->label() . '"');
            }

            return DB::transaction(function () use ($tradeOffer, $data) {
                $this->attachBooksToTradeOffer($tradeOffer, $data['sender_items'], $data['receiver_items']);
                TradeOfferUpdatedEvent::dispatch($tradeOffer, Auth::user());
                return $tradeOffer->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function delete(TradeOffer $tradeOffer): bool
    {
        try {
            return !!$tradeOffer->deleteOrFail();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function items(TradeOffer $tradeOffer): Collection
    {
        return $tradeOffer->books()->withoutTrashed()->get();
    }

    #[Override]
    public function bySender(User $sender): Collection
    {
        return TradeOffer::where('sender_id', (string)$sender->id)
            ->withoutTrashed()
            ->get();
    }

    #[Override]
    public function byReceiver(User $receiver): Collection
    {
        return TradeOffer::where('receiver_id', (string)$receiver->id)
            ->withoutTrashed()
            ->get();
    }

    #[Override]
    public function accept(TradeOffer $tradeOffer): TradeOffer | bool
    {
        try {
            $tradeOffer->updateOrFail(['status' => TradeOfferStatusEnum::Accepted]);
            return $tradeOffer->refresh();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function reject(TradeOffer $tradeOffer): TradeOffer | bool
    {
        try {
            return DB::transaction(function () use ($tradeOffer) {
                $tradeOffer->updateOrFail(['status' => TradeOfferStatusEnum::Rejected]);

                foreach ($tradeOffer->books()->get() as $book) {
                    $book->updateOrFail(['is_available' => true, 'trade_offer_id' => null]);
                }

                return $tradeOffer->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function cancel(TradeOffer $tradeOffer): TradeOffer | bool
    {
        try {
            return DB::transaction(function () use ($tradeOffer) {
                $tradeOffer->updateOrFail(['status' => TradeOfferStatusEnum::Canceled]);

                foreach ($tradeOffer->books()->get() as $book) {
                    $book->updateOrFail(['is_available' => true, 'trade_offer_id' => null]);
                }

                return $tradeOffer->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function finish(TradeOffer $tradeOffer): TradeOffer | bool
    {
        try {
            return DB::transaction(function () use ($tradeOffer) {
                $tradeOffer->updateOrFail(['status' => TradeOfferStatusEnum::Finished]);

                foreach ($tradeOffer->books()->get() as $book) {
                    $newOwnerId = $book->user_id === $tradeOffer->sender_id ? $tradeOffer->receiver_id : $tradeOffer->sender_id;
                    $book->updateOrFail(['user_id' => $newOwnerId, 'is_available' => true, 'trade_offer_id' => null]);
                }

                return $tradeOffer->refresh();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function tradeHistory(User $user): \Illuminate\Support\Collection
    {
        $bySender = $this->bySender($user);
        $byReceiver = $this->byReceiver($user);
        $history = $bySender->merge($byReceiver);

        $data = [];

        foreach (TradeOfferStatusEnum::cases() as $case) {
            $data[$case->value] = $history->where('status', $case);
        }

        return collect($data);
    }

    /**
     * @throws Throwable
     */
    private function attachBooksToTradeOffer(TradeOffer $tradeOffer, array $senderItems = [], array $receiverItems = []): void
    {
        DB::transaction(function () use ($tradeOffer, $senderItems, $receiverItems) {
            $senderItems = array_values(array_unique($senderItems));

            $senderBooks = Book::where('user_id', $tradeOffer->sender_id)
                ->whereIn('id', $senderItems)
                ->where('is_available', true)
                ->withoutTrashed()
                ->lockForUpdate();

            $senderBooksCount = $senderBooks->count();

            if ($senderBooksCount !== count($senderItems)) {
                throw new Exception("Пользователи должны владеть всеми книгами, участвующими в сделке");
            }

            $receiverItems = array_values(array_unique($receiverItems));

            $receiverBooks = Book::where('user_id', $tradeOffer->receiver_id)
                ->whereIn('id', $receiverItems)
                ->where('is_available', true)
                ->withoutTrashed()
                ->lockForUpdate();

            $receiverBooksCount = $receiverBooks->count();

            if ($receiverBooksCount !== count($receiverItems)) {
                throw new Exception("Пользователи должны владеть всеми книгами, участвующими в сделке");
            }

            Book::where('trade_offer_id', $tradeOffer->id)
                ->updateOrFail([
                    'trade_offer_id' => null,
                    'is_available' => true
                ]);

            Book::whereIn('id', array_merge($senderItems, $receiverItems))
                ->updateOrFail([
                    'trade_offer_id' => $tradeOffer->id,
                    'is_available' => false
                ]);
        });
    }
}
