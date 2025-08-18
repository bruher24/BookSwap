<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\GenreServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(GenreServiceInterface $genreService): JsonResponse
    {
        $genres = $genreService->getAll();
        $genreResourceCollection = GenreResource::collection($genres);
        return ResponseHelper::successResponse([
            'genres' => $genreResourceCollection,
        ]);
    }

    public function store(GenreServiceInterface $genreService, StoreGenreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $genre = $genreService->create($validated);
        if (!$genre) {
            return ResponseHelper::errorResponse(['Ошибка при создании жанра']);
        }
        $genreResource = new GenreResource($genre);
        return ResponseHelper::successResponse([
            'genre' => $genreResource,
        ], 'Жанр успешно создан');
    }

    public function show(GenreServiceInterface $genreService, string $id): JsonResponse
    {
        $genre = $genreService->get($id);
        if (!$genre) {
            return ResponseHelper::errorResponse(['Ошибка при получении жанра']);
        }
        $genreResource = new GenreResource($genre);
        return ResponseHelper::successResponse([
            'genre' => $genreResource,
        ]);
    }

    public function update(GenreServiceInterface $genreService, UpdateGenreRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        if (!$genreService->update($id, $validated)) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении жанра']);
        }
        return ResponseHelper::successResponse([], 'Жанр успешно обновлен');
    }

    public function destroy(GenreServiceInterface $genreService, string $id): JsonResponse
    {
        if (!$genreService->delete($id)) {
            return ResponseHelper::errorResponse(['Ошибка при удалении жанра']);
        }
        return ResponseHelper::successResponse([], 'Жанр успешно удален');
    }

    // TODO: убрать отсюда или добавить роут
    public function books(Request $request, BookServiceInterface $bookService, string $id): JsonResponse
    {
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byGenre($genre, $filters);

        return ResponseHelper::successResponse([
            'books' => $books,
            'params' => $params,
            'filters' => $filters,
            'genre' => $genre,
        ]);
    }
}
