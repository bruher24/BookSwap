<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\SuccessResource;
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
        $data = ['genres' => GenreResource::collection($genres)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(GenreServiceInterface $genreService, StoreGenreRequest $request): JsonResponse
    {
        Gate::authorize('create', Genre::class);

        $validated = $request->validated();
        $genre = $genreService->create($validated);

        if (!$genre) {
            $errors = ['Ошибка при создании жанра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['genre' => new GenreResource($genre)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Genre $genre): JsonResponse
    {
        $data = ['genre' => new GenreResource($genre)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(GenreServiceInterface $genreService, UpdateGenreRequest $request, Genre $genre): JsonResponse
    {
        Gate::authorize('update', $genre);

        $validated = $request->validated();
        $genre = $genreService->update($genre, $validated);

        if (!$genre) {
            $errors = ['Ошибка при обновлении жанра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['genre' => new GenreResource($genre)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(GenreServiceInterface $genreService, Genre $genre): JsonResponse
    {
        Gate::authorize('delete', $genre);

        if (!$genreService->delete($genre)) {
            $errors = ['Ошибка при удалении жанра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
