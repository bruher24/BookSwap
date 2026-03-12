<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\GenreServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GenreController extends Controller
{
    public function index(GenreServiceInterface $genreService): JsonResponse
    {
        $genres = $genreService->getAll();
        $genreResourceCollection = GenreResource::collection($genres);
        $data = ['genres' => $genreResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(GenreServiceInterface $genreService, StoreGenreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $genre = $genreService->create($validated);

        if (!$genre) {
            $errors = ['Ошибка при создании жанра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $genreResource = new GenreResource($genre);
        $data = ['genre' => $genreResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(GenreServiceInterface $genreService, string $id): JsonResponse
    {
        $genre = $genreService->get($id);

        if (!$genre) {
            $errors = ['Ошибка при получении жанра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $genreResource = new GenreResource($genre);
        $data = ['genre' => $genreResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(GenreServiceInterface $genreService, UpdateGenreRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        $genre = $genreService->update($id, $validated);
        if (!$genre) {
            $errors = ['Ошибка при обновлении жанра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $genreResource = new GenreResource($genre);
        $data = ['genre' => $genreResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(GenreServiceInterface $genreService, string $id): JsonResponse
    {
        if (!$genreService->delete($id)) {
            $errors = ['Ошибка при удалении жанра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
