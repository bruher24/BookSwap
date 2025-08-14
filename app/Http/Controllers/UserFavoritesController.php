<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFavoritesController extends Controller
{
    public function index()
    {
    }

    public function like()
    {
    }

    public function dislike()
    {
    }

    // TODO: разбить на методы выше
    public function updateFavorites(UserServiceInterface $userService, Request $request, User $user): JsonResponse
    {
        // TODO: разбить на два метода add и remove
        $book_id = $request->input('book_id');
        $isLiked = $request->input('isLiked');

        if (!$isLiked) {
            $userService->addToFavorites($user->id, $book_id);
        } else {
            $userService->removeFromFavorites($user->id, $book_id);
        }

        return ResponseHelper::successResponse('Добавлено в избранное');
    }
}
