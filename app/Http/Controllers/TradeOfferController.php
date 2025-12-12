<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTradeOfferRequest;
use App\Http\Requests\UpdateTradeOfferRequest;
use App\Http\Resources\TradeOfferResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\TradeOfferServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class TradeOfferController extends Controller
{
    public function index(TradeOfferServiceInterface $tradeOfferService): JsonResource
    {
        $tradeOffers = $tradeOfferService->getAll();
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);
        $data = ['tradeOffers' => $tradeOfferResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function store(TradeOfferServiceInterface $tradeOfferService, StoreTradeOfferRequest $request): JsonResource
    {
        $validated = $request->validated();
        $tradeOffer = $tradeOfferService->create($validated);

        if (!$tradeOffer) {
            $errors = ['Ошибка при создании сделки'];
            return new FailureResource(['errors' => $errors]);
        }

        $tradeOfferResource = new TradeOfferResource($tradeOffer);
        $data = ['tradeOffer' => $tradeOfferResource];

        return new SuccessResource(['data' => $data]);
    }

    public function show(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResource
    {
        $tradeOffer = $tradeOfferService->get($id);

        if (!$tradeOffer) {
            $errors = ['Ошибка при получении сделки'];
            return new FailureResource(['errors' => $errors]);
        }

        $tradeOfferResource = new TradeOfferResource($tradeOffer);
        $data = ['tradeOffer' => $tradeOfferResource];

        return new SuccessResource(['data' => $data]);
    }

    public function update(
        TradeOfferServiceInterface $tradeOfferService,
        UpdateTradeOfferRequest $request,
        string $id
    ): JsonResource {
        $validated = $request->validated();

        if (!$tradeOfferService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении сделки'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }

    public function destroy(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResource
    {
        if (!$tradeOfferService->delete($id)) {
            $errors = ['Ошибка при удалении сделки'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }

    public function bySender(TradeOfferServiceInterface $tradeOfferService, string $senderId): JsonResource
    {
        $tradeOffers = $tradeOfferService->bySender($senderId);
        $statusCode = $tradeOffers->isEmpty() ? 204 : 200;
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);

        return new SuccessResource(['tradeOffers' => $tradeOfferResourceCollection, 'statusCode' => $statusCode]);
    }

    public function byReceiver(TradeOfferServiceInterface $tradeOfferService, string $receiverId): JsonResource
    {
        $tradeOffers = $tradeOfferService->byReceiver($receiverId);
        $statusCode = $tradeOffers->isEmpty() ? 204 : 200;
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);

        return new SuccessResource(['tradeOffers' => $tradeOfferResourceCollection, 'statusCode' => $statusCode]);
    }

    public function accept(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResource
    {
        if (!$tradeOfferService->accept($id)) {
            return new FailureResource(['errors' => ['Ошибка при принятии предложения обмена']]);
        }

        return new SuccessResource([]);
    }

    public function reject(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResource
    {
        if (!$tradeOfferService->reject($id)) {
            return new FailureResource(['errors' => ['Ошибка при отклонении предложения обмена']]);
        }

        return new SuccessResource([]);
    }
}
