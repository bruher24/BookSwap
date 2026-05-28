<?php

namespace App\Services;

use App\Enums\TradeOfferStatus;
use App\Interfaces\TradeOfferServiceInterface;
use App\Interfaces\UserServiceInterface;
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
    public function __construct()
    {
    }

    public function create(array $data): TradeOffer|false
    {
        try {
            DB::beginTransaction();

            $data['status'] = TradeOfferStatus::Pending;

            $tradeOffer = new TradeOffer($data);

            if (!$tradeOffer->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            foreach ($data['trade_offer_items'] as $itemData) {
                $tradeOffer->items()->create(['book_id' => $itemData]);
            }

            DB::commit();
            return $tradeOffer->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
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
                throw new Exception("Можно изменять только сделки со статусом 'Ожидает'");
            }

            unset($data['status']);

            $senderItems = array_unique($data['sender_items'] ?? []);
            $senderBooks = Book::where('user_id', $tradeOffer->sender_id)
                ->whereIn('id', $senderItems)
                ->where('is_available', true);
            $senderBooksCount = $senderBooks->count();

            if ($senderBooksCount !== count($senderItems)) {
                throw new Exception("Пользователи должны владеть всеми книгами, участвующими в сделке");
            }

            $receiverItems = array_unique($data['receiver_items'] ?? []);
            $receiverBooks = Book::where('user_id', $tradeOffer->receiver_id)
                ->whereIn('id', $receiverItems)
                ->where('is_available', true);
            $receiverBooksCount = $receiverBooks->count();

            if ($receiverBooksCount !== count($receiverItems)) {
                throw new Exception("Пользователи должны владеть всеми книгами, участвующими в сделке");
            }


            $tradeOfferItems = array_merge($senderItems, $receiverItems);

            $tradeOffer->items()->delete();

            $tradeOffer->items()->createMany(
                array_map(
                    fn ($bookId) => ['book_id' => $bookId],
                    $tradeOfferItems
                )
            );

            $senderBooks->update(['is_available' => false]);
            $receiverBooks->update(['is_available' => false]);

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
        $bookIds = $tradeOffer->items()->pluck('book_id')->toArray();

        return Book::whereIn('id', $bookIds)->get();
    }

    public function bySender(User $sender): Collection
    {
        return $this->where('sender_id', $sender->id);
    }

    public function byReceiver(User $receiver): Collection
    {
        return $this->where('receiver_id', $receiver->id);
    }

    // TODO: нужно менять не только статус сделки, но и доступность и владельца книг
    public function accept(TradeOffer $tradeOffer): bool
    {
        try {
            $tradeOffer->status = TradeOfferStatus::Accepted;
            return $tradeOffer->saveOrFail();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    // TODO: нужно менять не только статус сделки, но и доступность и владельца книг
    public function reject(TradeOffer $tradeOffer): bool
    {
        try {
            $tradeOffer->status = TradeOfferStatus::Rejected;
            return $tradeOffer->saveOrFail();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
