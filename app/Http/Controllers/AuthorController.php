<?php

namespace App\Http\Controllers;

use App\Events\AuthorCreated;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Interfaces\AuthorServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

final class AuthorController extends Controller
{
    public function index(AuthorServiceInterface $authorService): JsonResponse
    {
        $authors = $authorService->getAll();
        $authorResourceCollection = AuthorResource::collection($authors);

        Redis::publish('listeners', 'TEST MESSAGE FROM LARAVEL');
        AuthorCreated::dispatch('TEST MSG');

        return ResponseHelper::successResponse([
            'authors' => $authorResourceCollection,
        ]);
    }

    public function store(AuthorServiceInterface $authorService, StoreAuthorRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $author = $authorService->create($validated);
        if (!$author) {
            return ResponseHelper::errorResponse(400, ['Ошибка при создании автора']);
        }
        $authorResource = new AuthorResource($author);

        return ResponseHelper::successResponse([
            'author' => $authorResource,
        ], 'Автор успешно создан');
    }

    public function show(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        $author = $authorService->get($id);
        if (!$author) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении автора']);
        }
        $authorResource = new AuthorResource($author);

        return ResponseHelper::successResponse([
            'author' => $authorResource,
        ]);
    }

    public function update(
        AuthorServiceInterface $authorService,
        UpdateAuthorRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        if (!$authorService->update($id, $validated)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении автора']);
        }

        return ResponseHelper::successResponse([], 'Автор успешно обновлен');
    }

    public function destroy(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        if (!$authorService->delete($id)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при удалении автора']);
        }

        return ResponseHelper::successResponse([], 'Автор успешно удален');
    }
}
