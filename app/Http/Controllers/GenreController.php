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
        $covers = $genreService->getAll();
        return ResponseHelper::successResponse('Success', [
            'covers' => $covers,
        ]);
    }

    public function create(GenreServiceInterface $genreService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(GenreServiceInterface $genreService, StoreGenreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $cover = $genreService->create($validated);
        if (!$cover) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'cover' => $cover,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(GenreServiceInterface $genreService, string $id): JsonResponse
    {
        $cover = $genreService->get($id);
        if (!$cover) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'cover' => $cover,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GenreServiceInterface $genreService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GenreServiceInterface $genreService, UpdateGenreRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        $updated = $genreService->update($id, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GenreServiceInterface $genreService, string $id): JsonResponse
    {
        $deleted = $genreService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    public function books(Request $request, BookServiceInterface $bookService, Genre $genre): JsonResponse
    {
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byGenre($genre, $filters);

        return view('genres.books', compact('books', 'params', 'filters', 'genre'));
    }
}
