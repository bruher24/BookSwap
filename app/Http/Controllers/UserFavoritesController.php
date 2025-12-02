<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Services\UserFavoritesService;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserFavoritesController extends Controller
{
    public function index(UserFavoritesService $userFavoritesService, string $userId): JsonResource
    {
        $favorites = $userFavoritesService->favorites($userId);
        $favoritesResourceCollection = BookResource::collection($favorites);
        $data = ['favorites' => $favoritesResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function like(UserFavoritesService $userFavoritesService, string $userId, string $bookId): JsonResource
    {
        if (!$userFavoritesService->addToFavorites($userId, $bookId)) {
            $errors = ['Ошибка добавления книги в избранное'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }

    public function dislike(UserFavoritesService $userFavoritesService, string $userId, string $bookId): JsonResource
    {
        if (!$userFavoritesService->removeFromFavorites($userId, $bookId)) {
            $errors = ['Ошибка удаления книги из избранного'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }
}
