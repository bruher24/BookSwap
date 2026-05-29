<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTradeOfferRequest;
use App\Http\Requests\UpdateTradeOfferRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\TradeOfferResource;
use App\Interfaces\TradeOfferServiceInterface;
use App\Models\TradeOffer;
use App\Models\User;
use App\Services\TradeOfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class TradeOfferController extends Controller
{
    public function index(TradeOfferServiceInterface $tradeOfferService): JsonResponse
    {
        Gate::authorize('viewAny', TradeOffer::class);

        $tradeOffers = $tradeOfferService->getAll();
        $data = ['tradeOffers' => TradeOfferResource::collection($tradeOffers)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(TradeOfferServiceInterface $tradeOfferService, StoreTradeOfferRequest $request): JsonResponse
    {
        Gate::authorize('create', [TradeOffer::class, $request->input('sender_id')]);

        $validated = $request->validated();
        $tradeOffer = $tradeOfferService->create($validated);

        if (!$tradeOffer) {
            $errors = ['Ошибка при создании сделки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['tradeOffer' => new TradeOfferResource($tradeOffer)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TradeOffer $tradeOffer): JsonResponse
    {
        Gate::authorize('view', $tradeOffer);

        $data = ['tradeOffer' => new TradeOfferResource($tradeOffer)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(TradeOfferServiceInterface $tradeOfferService, UpdateTradeOfferRequest $request, TradeOffer $tradeOffer): JsonResponse
    {
        Gate::authorize('update', $tradeOffer);

        $validated = $request->validated();
        $tradeOffer = $tradeOfferService->update($tradeOffer, $validated);

        if (!$tradeOffer) {
            $errors = ['Ошибка при обновлении сделки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['tradeOffer' => new TradeOfferResource($tradeOffer)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(TradeOfferServiceInterface $tradeOfferService, TradeOffer $tradeOffer): JsonResponse
    {
        Gate::authorize('delete', $tradeOffer);

        if (!$tradeOfferService->delete($tradeOffer)) {
            $errors = ['Ошибка при удалении сделки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function items(TradeOfferService $tradeOfferService, TradeOffer $tradeOffer): JsonResponse
    {
        Gate::authorize('view', $tradeOffer);

        $items = $tradeOfferService->items($tradeOffer);
        $data = ['items' => BookResource::collection($items)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function bySender(TradeOfferServiceInterface $tradeOfferService, User $sender): JsonResponse
    {
        Gate::authorize('bySender', [TradeOffer::class, $sender]);

        $tradeOffers = $tradeOfferService->bySender($sender);
        $data = ['tradeOffers' => TradeOfferResource::collection($tradeOffers)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function byReceiver(TradeOfferServiceInterface $tradeOfferService, User $receiver): JsonResponse
    {
        Gate::authorize('byReceiver', [TradeOffer::class, $receiver]);

        $tradeOffers = $tradeOfferService->byReceiver($receiver);
        $data = ['tradeOffers' => TradeOfferResource::collection($tradeOffers)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function accept(TradeOfferServiceInterface $tradeOfferService, TradeOffer $tradeOffer): JsonResponse
    {
        Gate::authorize('accept', $tradeOffer);

        if (!$tradeOfferService->accept($tradeOffer)) {
            $errors = ['Ошибка при принятии предложения обмена'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function reject(TradeOfferServiceInterface $tradeOfferService, string $tradeOfferId): JsonResponse
    {
        $tradeOffer = $tradeOfferService->get($tradeOfferId);

        if (!$tradeOffer) {
            return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('reject', $tradeOffer);

        if (!$tradeOfferService->reject($tradeOffer)) {
            $errors = ['Ошибка при отклонении предложения обмена'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
