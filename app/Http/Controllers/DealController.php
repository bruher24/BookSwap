<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDealRequest;
use App\Http\Requests\UpdateDealRequest;
use App\Http\Resources\DealResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\DealServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class DealController extends Controller
{
    public function index(DealServiceInterface $dealService): JsonResource
    {
        $deals = $dealService->getAll();
        $dealResourceCollection = DealResource::collection($deals);
        $data = ['deals' => $dealResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function store(DealServiceInterface $dealService, StoreDealRequest $request): JsonResource
    {
        $validated = $request->validated();
        $deal = $dealService->create($validated);

        if (!$deal) {
            $errors = ['Ошибка при создании сделки'];
            return new FailureResource(['errors' => $errors]);
        }

        $dealResource = new DealResource($deal);
        $data = ['deal' => $dealResource];

        return new SuccessResource(['data' => $data]);
    }

    public function show(DealServiceInterface $dealService, string $id): JsonResource
    {
        $deal = $dealService->get($id);

        if (!$deal) {
            $errors = ['Ошибка при получении сделки'];
            return new FailureResource(['errors' => $errors]);
        }

        $dealResource = new DealResource($deal);
        $data = ['deal' => $dealResource];

        return new SuccessResource(['data' => $data]);
    }

    public function update(DealServiceInterface $dealService, UpdateDealRequest $request, string $id): JsonResource
    {
        $validated = $request->validated();

        if (!$dealService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении сделки'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }

    public function destroy(DealServiceInterface $dealService, string $id): JsonResource
    {
        if (!$dealService->delete($id)) {
            $errors = ['Ошибка при удалении сделки'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }
}
