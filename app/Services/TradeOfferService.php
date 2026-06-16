<?php

namespace App\Services;

use App\Enums\TradeOfferStatus;
use App\Interfaces\TradeOfferServiceInterface;
use App\Models\Book;
use App\Models\TradeOffer;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class TradeOfferService implements TradeOfferServiceInterface
{
    public function create(array $data): TradeOffer|false
    {
        try {
            DB::beginTransaction();

            $data['status'] = TradeOfferStatus::Pending;
            $tradeOffer = TradeOffer::create($data);
            $this->attachBooksToTradeOffer($tradeOffer, $data['sender_items'], $data['receiver_items']);
            DB::commit();
            return $tradeOffer->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    /**
     * @throws Exception
     */
    private function attachBooksToTradeOffer(TradeOffer $tradeOffer, array $senderItems = [], array $receiverItems = []): void
    {
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
            ->update([
                'trade_offer_id' => null,
                'is_available' => true
            ]);

        Book::whereIn('id', array_merge($senderItems, $receiverItems))
            ->update([
                'trade_offer_id' => $tradeOffer->id,
                'is_available' => false
            ]);
    }

    public function get(string $id): TradeOffer|false
    {
        try {
            return TradeOffer::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

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

    public function where(string $field, string $value): Collection
    {
        try {
            return TradeOffer::where($field, $value)->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    public function update(TradeOffer $tradeOffer, array $data): TradeOffer|false
    {
        try {
            DB::beginTransaction();

            if ($tradeOffer->status !== TradeOfferStatus::Pending) {
                throw new Exception('Можно изменять только сделки со статусом "' . TradeOfferStatus::Pending->label() . '"');
            }

            $this->attachBooksToTradeOffer($tradeOffer, $data['sender_items'], $data['receiver_items']);
            DB::commit();
            return $tradeOffer->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function delete(TradeOffer $tradeOffer): bool
    {
        try {
            DB::beginTransaction();
            $tradeOffer->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function items(TradeOffer $tradeOffer): Collection
    {
        return $tradeOffer->books()->withoutTrashed()->get();
    }

    public function bySender(User $sender): Collection
    {
        return $this->where('sender_id', $sender->id);
    }

    public function byReceiver(User $receiver): Collection
    {
        return $this->where('receiver_id', $receiver->id);
    }

    public function accept(TradeOffer $tradeOffer): bool
    {
        try {
            return $tradeOffer->updateOrFail(['status' => TradeOfferStatus::Accepted]);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function reject(TradeOffer $tradeOffer): bool
    {
        try {
            if ($tradeOffer->updateOrFail(['status' => TradeOfferStatus::Rejected])) {
                foreach ($tradeOffer->books()->get() as $book) {
                    $book->update(['is_available' => true]);
                }

                return true;
            }

            return false;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function finish(TradeOffer $tradeOffer): bool
    {
        try {
            if ($tradeOffer->updateOrFail(['status' => TradeOfferStatus::Finished])) {
                foreach ($tradeOffer->books()->get() as $book) {
                    $newOwnerId = $book->user_id === $tradeOffer->sender_id ? $tradeOffer->receiver_id : $tradeOffer->sender_id;
                    $book->update(['user_id' => $newOwnerId, 'is_available' => true]);
                }

                return true;
            }

            return false;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function tradeHistory(User $user): \Illuminate\Support\Collection
    {
        $history = TradeOffer::query()
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->withoutTrashed()
            ->get();

        return collect([
            TradeOfferStatus::Pending->value => $history->where('status', TradeOfferStatus::Pending),
            TradeOfferStatus::Accepted->value => $history->where('status', TradeOfferStatus::Accepted),
            TradeOfferStatus::Rejected->value => $history->where('status', TradeOfferStatus::Rejected),
            TradeOfferStatus::Finished->value => $history->where('status', TradeOfferStatus::Finished),
        ]);
    }
}
