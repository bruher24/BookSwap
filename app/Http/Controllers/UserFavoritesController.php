<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
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
        $data = ['favorites' => BookResource::collection($favorites)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function like(UserFavoritesService $userFavoritesService, User $user, Book $book): JsonResponse
    {
        Gate::authorize('favorites', $user);

        if (!$userFavoritesService->addToFavorites($user, $book)) {
            $errors = ['Ошибка добавления книги в избранное'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function dislike(UserFavoritesService $userFavoritesService, User $user, Book $book): JsonResponse
    {
        Gate::authorize('favorites', $user);

        if (!$userFavoritesService->removeFromFavorites($user, $book)) {
            $errors = ['Ошибка удаления книги из избранного'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
