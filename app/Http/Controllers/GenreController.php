<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\GenreServiceInterface;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(GenreServiceInterface $genreService): JsonResponse
    {
        $genres = $genreService->getAll();
        return ResponseHelper::successResponse('Success', [
            'genres' => $genres,
        ]);
    }

    public function store(GenreServiceInterface $genreService, StoreGenreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $genre = $genreService->create($validated);
        if (!$genre) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании жанра',
            ]);
        }
        return ResponseHelper::successResponse('Жанр успешно создан', [
            'genre' => $genre,
        ]);
    }

    public function show(GenreServiceInterface $genreService, string $id): JsonResponse
    {
        $genre = $genreService->get($id);
        if (!$genre) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении жанра',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'genre' => $genre,
        ]);
    }

    public function update(GenreServiceInterface $genreService, UpdateGenreRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        $updated = $genreService->update($id, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении жанра',
            ]);
        }
        return ResponseHelper::successResponse('Жанр успешно обновлен');
    }

    public function destroy(GenreServiceInterface $genreService, string $id): JsonResponse
    {
        $deleted = $genreService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении жанра',
            ]);
        }
        return ResponseHelper::successResponse('Жанр успешно удален');
    }

    // TODO: убрать отсюда
    public function books(Request $request, BookServiceInterface $bookService, Genre $genre): JsonResponse
    {
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byGenre($genre, $filters);

        return ResponseHelper::successResponse('Success', [
            'books' => $books,
            'params' => $params,
            'filters' => $filters,
            'genre' => $genre,
        ]);
    }
}
