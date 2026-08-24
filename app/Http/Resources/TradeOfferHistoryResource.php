<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use Override;

final class TradeOfferHistoryResource extends JsonApiResource
{
    #[Override]
    public function toId(Request $request): string
    {
        return 'trade-offer-history';
    }

    #[Override]
    public function toType(Request $request): string
    {
        return 'trade_offer_histories';
    }

    #[Override]
    public function toAttributes(Request $request): array
    {
        return [
            'pending' => TradeOfferResource::collection($this->resource['pending'] ?? [])->toArray($request),
            'accepted' => TradeOfferResource::collection($this->resource['accepted'] ?? [])->toArray($request),
            'rejected' => TradeOfferResource::collection($this->resource['rejected'] ?? [])->toArray($request),
            'canceled' => TradeOfferResource::collection($this->resource['canceled'] ?? [])->toArray($request),
            'finished' => TradeOfferResource::collection($this->resource['finished'] ?? [])->toArray($request),
        ];
    }
}
