<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTradeOfferRequest;
use App\Http\Requests\UpdateTradeOfferRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\TradeOfferResource;
use App\Interfaces\TradeOfferServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TradeOfferController extends Controller
{
    public function index(TradeOfferServiceInterface $tradeOfferService): JsonResponse
    {
        $tradeOffers = $tradeOfferService->getAll();
        $statusCode = $tradeOffers->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);
        $data = ['tradeOffers' => $tradeOfferResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode($statusCode);
    }

    public function store(TradeOfferServiceInterface $tradeOfferService, StoreTradeOfferRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $tradeOffer = $tradeOfferService->create($validated);

        if (!$tradeOffer) {
            $errors = ['Ошибка при создании сделки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $tradeOfferResource = new TradeOfferResource($tradeOffer);
        $data = ['tradeOffer' => $tradeOfferResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResponse
    {
        $tradeOffer = $tradeOfferService->get($id);

        if (!$tradeOffer) {
            $errors = ['Ошибка при получении сделки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $tradeOfferResource = new TradeOfferResource($tradeOffer);
        $data = ['tradeOffer' => $tradeOfferResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(
        TradeOfferServiceInterface $tradeOfferService,
        UpdateTradeOfferRequest    $request,
        string                     $id
    ): JsonResponse
    {
        $validated = $request->validated();
        $tradeOffer = $tradeOfferService->update($id, $validated);

        if (!$tradeOffer) {
            $errors = ['Ошибка при обновлении сделки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $tradeOfferResource = new TradeOfferResource($tradeOffer);
        $data = ['tradeOffer' => $tradeOfferResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResponse
    {
        if (!$tradeOfferService->delete($id)) {
            $errors = ['Ошибка при удалении сделки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function bySender(string $senderId): JsonResponse
    {
        $tradeOffers = $this->service->bySender($senderId);
        $statusCode = $tradeOffers->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);
        $data = ['tradeOffers' => $tradeOfferResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode($statusCode);
    }

    public function byReceiver(string $receiverId): JsonResponse
    {
        $tradeOffers = $this->service->byReceiver($receiverId);
        $statusCode = $tradeOffers->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);
        $data = ['tradeOffers' => $tradeOfferResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode($statusCode);
    }

    public function accept(string $id): JsonResponse
    {
        if (!$this->service->accept($id)) {
            $errors = ['Ошибка при принятии предложения обмена'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function reject(string $id): JsonResponse
    {
        if (!$this->service->reject($id)) {
            $errors = ['Ошибка при отклонении предложения обмена'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
