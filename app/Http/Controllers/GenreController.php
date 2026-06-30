<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use App\Interfaces\GenreServiceInterface;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class GenreController extends Controller
{
    public function index(GenreServiceInterface $genreService): JsonResponse
    {
        $genres = $genreService->getAll();

        return GenreResource::collection($genres)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(GenreServiceInterface $genreService, StoreGenreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $genre = $genreService->create($validated);

        if (!$genre) {
            return $this->errorResponse('Ошибка при создании жанра', Response::HTTP_BAD_REQUEST);
        }

        return (new GenreResource($genre))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Genre $genre): JsonResponse
    {
        return (new GenreResource($genre))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(GenreServiceInterface $genreService, UpdateGenreRequest $request, Genre $genre): JsonResponse
    {
        $validated = $request->validated();
        $genre = $genreService->update($genre, $validated);

        if (!$genre) {
            return $this->errorResponse('Ошибка при обновлении жанра', Response::HTTP_BAD_REQUEST);
        }

        return (new GenreResource($genre))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(GenreServiceInterface $genreService, int $genreId): JsonResponse
    {
        $genre = $genreService->get($genreId);

        if (!$genre) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        if (!$genreService->delete($genre)) {
            return $this->errorResponse('Ошибка при удалении жанра', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
