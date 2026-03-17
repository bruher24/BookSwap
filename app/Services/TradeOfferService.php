<?php

namespace App\Services;

use App\Interfaces\TradeOfferServiceInterface;
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
            $tradeOffer = new TradeOffer($data);

            if (!$tradeOffer->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            DB::commit();
            return $tradeOffer->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function get(string $id): TradeOffer|false
    {
        try {
            return TradeOffer::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
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
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function where(string $field, string $value): Collection
    {
        try {
            return TradeOffer::where($field, $value)->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    public function update(TradeOffer $tradeOffer, array $data): TradeOffer|false
    {
        try {
            DB::beginTransaction();
            $tradeOffer->updateOrFail($data);
            DB::commit();
            return $tradeOffer->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
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
            Log::error($e->getMessage());
            return false;
        }
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
            $tradeOffer->accept();
            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function reject(TradeOffer $tradeOffer): bool
    {
        try {
            $tradeOffer->reject();
            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
