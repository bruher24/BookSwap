<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\DealServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function store(DealServiceInterface $dealService, Request $request): JsonResponse
    {
        $deal = $dealService->create($request->all());
        if (!$deal) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании сделки',
            ]);
        }
        return ResponseHelper::successResponse('Сделка успешно создана', [
            'deal' => $deal,
        ]);
    }

    public function show(DealServiceInterface $dealService, string $id): JsonResponse
    {
        $deal = $dealService->get($id);
        if (!$deal) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении сделки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'deal' => $deal,
        ]);
    }
}
