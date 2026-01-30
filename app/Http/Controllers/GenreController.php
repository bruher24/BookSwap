<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\GenreResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\GenreServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class GenreController extends Controller
{
    public function index(GenreServiceInterface $genreService): JsonResource
    {
        $genres = $genreService->getAll();
        $genreResourceCollection = GenreResource::collection($genres);
        $data = ['genres' => $genreResourceCollection];

        return new SuccessResource($data);
    }

    public function store(GenreServiceInterface $genreService, StoreGenreRequest $request): JsonResource
    {
        $validated = $request->validated();
        $genre = $genreService->create($validated);

        if (!$genre) {
            $errors = ['Ошибка при создании жанра'];
            return new FailureResource($errors);
        }

        $genreResource = new GenreResource($genre);
        $data = ['genre' => $genreResource];

        return new SuccessResource($data);
    }

    public function show(GenreServiceInterface $genreService, string $id): JsonResource
    {
        $genre = $genreService->get($id);

        if (!$genre) {
            $errors = ['Ошибка при получении жанра'];
            return new FailureResource($errors);
        }

        $genreResource = new GenreResource($genre);
        $data = ['genre' => $genreResource];

        return new SuccessResource($data);
    }

    public function update(GenreServiceInterface $genreService, UpdateGenreRequest $request, string $id): JsonResource
    {
        $validated = $request->validated();
        if (!$genreService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении жанра'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }

    public function destroy(GenreServiceInterface $genreService, string $id): JsonResource
    {
        if (!$genreService->delete($id)) {
            $errors = ['Ошибка при удалении жанра'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }
}
