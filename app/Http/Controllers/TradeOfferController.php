<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTradeOfferRequest;
use App\Http\Requests\UpdateTradeOfferRequest;
use App\Http\Resources\TradeOfferResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\TradeOfferServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TradeOfferController extends Controller
{
    public function index(TradeOfferServiceInterface $tradeOfferService): JsonResponse
    {
        $tradeOffers = $tradeOfferService->getAll();
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);
        $data = ['tradeOffers' => $tradeOfferResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
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
        UpdateTradeOfferRequest $request,
        string $id
    ): JsonResponse {
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

    public function bySender(TradeOfferServiceInterface $tradeOfferService, string $senderId): JsonResponse
    {
        $tradeOffers = $tradeOfferService->bySender($senderId);
        $statusCode = $tradeOffers->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);
        $data = ['tradeOffers' => $tradeOfferResourceCollection, 'statusCode' => $statusCode];

        return (new SuccessResource($data))->response()->setStatusCode($statusCode);
    }

    public function byReceiver(TradeOfferServiceInterface $tradeOfferService, string $receiverId): JsonResponse
    {
        $tradeOffers = $tradeOfferService->byReceiver($receiverId);
        $statusCode = $tradeOffers->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);
        $data = ['tradeOffers' => $tradeOfferResourceCollection, 'statusCode' => $statusCode];

        return (new SuccessResource($data))->response()->setStatusCode($statusCode);
    }

    public function accept(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResponse
    {
        if (!$tradeOfferService->accept($id)) {
            return (new FailureResource(['errors' => ['Ошибка при принятии предложения обмена']]))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function reject(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResponse
    {
        if (!$tradeOfferService->reject($id)) {
            return (new FailureResource(['errors' => ['Ошибка при отклонении предложения обмена']]))
                ->response()
                ->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
