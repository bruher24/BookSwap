<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\DealResource;
use App\Interfaces\DealServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index(DealServiceInterface $dealService): JsonResponse
    {
        $deals = $dealService->getAll();
        $dealResourceCollection = DealResource::collection($deals);
        return ResponseHelper::successResponse([
            'deals' => $dealResourceCollection,
        ]);
    }

    public function store(DealServiceInterface $dealService, Request $request): JsonResponse
    {
        $deal = $dealService->create($request->all());
        $dealResource = new DealResource($deal);
        if (!$deal) {
            return ResponseHelper::errorResponse(['Ошибка при создании сделки']);
        }
        return ResponseHelper::successResponse([
            'deal' => $dealResource,
        ], 'Сделка успешно создана');
    }

    public function show(DealServiceInterface $dealService, string $id): JsonResponse
    {
        $deal = $dealService->get($id);
        $dealResource = new DealResource($deal);
        if (!$deal) {
            return ResponseHelper::errorResponse(['Ошибка при получении сделки']);
        }
        return ResponseHelper::successResponse([
            'deal' => $dealResource,
        ]);
    }

    public function update(DealServiceInterface $dealService, Request $request, string $id): JsonResponse
    {
        $updated = $dealService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении сделки']);
        }
        return ResponseHelper::successResponse([], 'Сделка успешно обновлена');
    }

    public function destroy(DealServiceInterface $dealService, string $id): JsonResponse
    {
        $deleted = $dealService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse(['Ошибка при удалении сделки']);
        }
        return ResponseHelper::successResponse([], 'Сделка успешно удалена');
    }

}
