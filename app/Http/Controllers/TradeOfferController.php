<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTradeOfferRequest;
use App\Http\Requests\UpdateTradeOfferRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\TradeOfferResource;
use App\Interfaces\TradeOfferServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Override;
use Symfony\Component\HttpFoundation\Response;

final class TradeOfferController extends CrudController
{
    public function __construct(TradeOfferServiceInterface $tradeOfferService)
    {
        $this->resourceClass = TradeOfferResource::class;
        $this->resourceKey = 'tradeOffer';
        $this->resourceCollectionKey = 'tradeOffers';
        $this->storeRequestClass = StoreTradeOfferRequest::class;
        $this->updateRequestClass = UpdateTradeOfferRequest::class;
        $this->createErrorMessage = 'Ошибка при создании сделки';
        $this->getErrorMessage = 'Ошибка при получении сделки';
        $this->updateErrorMessage = 'Ошибка при обновлении сделки';
        $this->deleteErrorMessage = 'Ошибка при удалении сделки';

        parent::__construct($tradeOfferService);
    }

    #[Override]
    protected function indexStatusCode(Collection $items): int
    {
        return $items->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
    }

    public function bySender(TradeOfferServiceInterface $tradeOfferService, string $senderId): JsonResponse
    {
        $tradeOffers = $tradeOfferService->bySender($senderId);
        $statusCode = $tradeOffers->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);
        $data = ['tradeOffers' => $tradeOfferResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode($statusCode);
    }

    public function byReceiver(TradeOfferServiceInterface $tradeOfferService, string $receiverId): JsonResponse
    {
        $tradeOffers = $tradeOfferService->byReceiver($receiverId);
        $statusCode = $tradeOffers->isEmpty() ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $tradeOfferResourceCollection = TradeOfferResource::collection($tradeOffers);
        $data = ['tradeOffers' => $tradeOfferResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode($statusCode);
    }

    public function accept(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResponse
    {
        if (!$tradeOfferService->accept($id)) {
            $errors = ['Ошибка при принятии предложения обмена'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function reject(TradeOfferServiceInterface $tradeOfferService, string $id): JsonResponse
    {
        if (!$tradeOfferService->reject($id)) {
            $errors = ['Ошибка при отклонении предложения обмена'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
