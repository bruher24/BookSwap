<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\BookResource;
use App\Services\UserFavoritesService;
use Illuminate\Http\JsonResponse;

class UserFavoritesController extends Controller
{
    public function index(UserFavoritesService $userFavoritesService, string $userId): JsonResponse
    {
        $favorites = $userFavoritesService->favorites($userId);
        $favoritesResourceCollection = BookResource::collection($favorites);
        return ResponseHelper::successResponse([
            'favorites' => $favoritesResourceCollection,
        ]);
    }

    public function like(UserFavoritesService $userFavoritesService, string $userId, string $bookId): JsonResponse
    {
        if (!$userFavoritesService->addToFavorites($userId, $bookId)) {
            return ResponseHelper::errorResponse(400, ['Ошибка добавления книги в избранное']);
        }
        return ResponseHelper::successResponse([], 'Книга добавлена в избранное');
    }

    public function dislike(UserFavoritesService $userFavoritesService, string $userId, string $bookId): JsonResponse
    {
        if (!$userFavoritesService->removeFromFavorites($userId, $bookId)) {
            return ResponseHelper::errorResponse(400, ['Ошибка удаления книги из избранного']);
        }
        return ResponseHelper::successResponse([], 'Книга удалена из избранного');
    }
}
