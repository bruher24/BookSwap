<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Services\UserFavoritesService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserFavoritesController extends Controller
{
    public function index(UserFavoritesService $userFavoritesService, string $userId): JsonResponse
    {
        $favorites = $userFavoritesService->favorites($userId);
        $favoritesResourceCollection = BookResource::collection($favorites);
        $data = ['favorites' => $favoritesResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function like(UserFavoritesService $userFavoritesService, string $userId, string $bookId): JsonResponse
    {
        if (!$userFavoritesService->addToFavorites($userId, $bookId)) {
            $errors = ['Ошибка добавления книги в избранное'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function dislike(UserFavoritesService $userFavoritesService, string $userId, string $bookId): JsonResponse
    {
        if (!$userFavoritesService->removeFromFavorites($userId, $bookId)) {
            $errors = ['Ошибка удаления книги из избранного'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
