<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\User;
use App\Services\UserFavoritesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class UserFavoritesController extends Controller
{
    public function index(UserFavoritesService $userFavoritesService, User $user): JsonResponse
    {
        Gate::authorize('favorites', $user);

        $favorites = $userFavoritesService->favorites($user);

        return BookResource::collection($favorites)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function like(UserFavoritesService $userFavoritesService, User $user, Book $book): JsonResponse
    {
        Gate::authorize('favorites', $user);

        if (!$userFavoritesService->addToFavorites($user, $book)) {
            return $this->errorResponse('Ошибка добавления книги в избранное', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse();
    }

    public function dislike(UserFavoritesService $userFavoritesService, User $user, Book $book): JsonResponse
    {
        Gate::authorize('favorites', $user);

        if (!$userFavoritesService->removeFromFavorites($user, $book)) {
            return $this->errorResponse('Ошибка удаления книги из избранного', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
