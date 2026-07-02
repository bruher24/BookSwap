<?php

namespace App\Http\Controllers;

use App\Enums\TradeOfferStatus;
use App\Http\Requests\StoreTradeOfferRequest;
use App\Http\Requests\UpdateTradeOfferRequest;
use App\Http\Requests\UpdateTradeOfferStatusRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\TradeOfferResource;
use App\Interfaces\TradeOfferServiceInterface;
use App\Models\TradeOffer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class TradeOfferController extends Controller
{
    public function index(TradeOfferServiceInterface $tradeOfferService): JsonResponse
    {
        $tradeOffers = $tradeOfferService->getAll();

        return TradeOfferResource::collection($tradeOffers)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(TradeOfferServiceInterface $tradeOfferService, StoreTradeOfferRequest $request): JsonResponse
    {
        Gate::authorize('create', [TradeOffer::class, $request->input('sender_id')]);

        $validated = $request->validated();
        $tradeOffer = $tradeOfferService->create($validated);

        if (!$tradeOffer) {
            return $this->errorResponse('Ошибка при создании сделки', Response::HTTP_BAD_REQUEST);
        }

        return (new TradeOfferResource($tradeOffer))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TradeOffer $tradeOffer): JsonResponse
    {
        Gate::authorize('view', $tradeOffer);

        return (new TradeOfferResource($tradeOffer))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(TradeOfferServiceInterface $tradeOfferService, UpdateTradeOfferRequest $request, TradeOffer $tradeOffer): JsonResponse
    {
        Gate::authorize('update', $tradeOffer);

        $validated = $request->validated();
        $tradeOffer = $tradeOfferService->update($tradeOffer, $validated);

        if (!$tradeOffer) {
            return $this->errorResponse('Ошибка при обновлении сделки', Response::HTTP_BAD_REQUEST);
        }

        return (new TradeOfferResource($tradeOffer))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(TradeOfferServiceInterface $tradeOfferService, int $tradeOfferId): JsonResponse
    {
        $tradeOffer = $tradeOfferService->get($tradeOfferId);

        if (!$tradeOffer) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        if (!$tradeOfferService->delete($tradeOffer)) {
            return $this->errorResponse('Ошибка при удалении сделки', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }

    public function items(TradeOfferServiceInterface $tradeOfferService, TradeOffer $tradeOffer): JsonResponse
    {
        Gate::authorize('view', $tradeOffer);

        $items = $tradeOfferService->items($tradeOffer);

        return BookResource::collection($items)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function updateStatus(UpdateTradeOfferStatusRequest $request, TradeOfferServiceInterface $tradeOfferService, TradeOffer $tradeOffer): JsonResponse
    {
        $validated = $request->validated();
        $status = TradeOfferStatus::tryFrom($validated['status']);

        switch ($status) {
            case TradeOfferStatus::Accepted:
                Gate::authorize('accept', $tradeOffer);
                $result = $tradeOfferService->accept($tradeOffer);
                break;
            case TradeOfferStatus::Rejected:
                Gate::authorize('reject', $tradeOffer);
                $result = $tradeOfferService->reject($tradeOffer);
                break;
            case TradeOfferStatus::Canceled:
                Gate::authorize('cancel', $tradeOffer);
                $result = $tradeOfferService->cancel($tradeOffer);
                break;
            case TradeOfferStatus::Finished:
                Gate::authorize('finish', $tradeOffer);
                $result = $tradeOfferService->finish($tradeOffer);
                break;
            default:
                $result = false;
        }

        if (!$result) {
            return $this->errorResponse('Ошибка при обновлении статуса сделки', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse();
    }
}
