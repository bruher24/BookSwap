<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\BookResource;
use App\Interfaces\AuthorServiceInterface;
use App\Interfaces\BookServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(AuthorServiceInterface $authorService): JsonResponse
    {
        $authors = $authorService->getAll();
        $authorResourceCollection = AuthorResource::collection($authors);
        return ResponseHelper::successResponse([
            'authors' => $authorResourceCollection,
        ]);
    }

    public function store(AuthorServiceInterface $authorService, StoreAuthorRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $author = $authorService->create($validated);
        $authorResource = new AuthorResource($author);
        if (!$author) {
            return ResponseHelper::errorResponse(['Ошибка при создании автора']);
        }
        return ResponseHelper::successResponse([
            'author' => $authorResource,
        ], 'Автор успешно создан');
    }

    public function show(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        $author = $authorService->get($id);
        $authorResource = new AuthorResource($author);
        if (!$author) {
            return ResponseHelper::errorResponse(['Ошибка при получении автора']);
        }
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
        $updated = $authorService->update($id, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении автора']);
        }
        return ResponseHelper::successResponse([], 'Автор успешно обновлен');
    }

    public function destroy(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        $deleted = $authorService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse(['Ошибка при удалении автора']);
        }
        return ResponseHelper::successResponse([], 'Автор успешно удален');
    }

    // TODO: убрать отсюда или добавить роут
    public function books(
        Request $request,
        AuthorServiceInterface $authorService,
        BookServiceInterface $bookService,
        string $id
    ): JsonResponse {
        // TODO: исправить

        $author = $authorService->get($id);

        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byAuthor($author, $filters);

        $bookResourceCollection = BookResource::collection($books);

        return ResponseHelper::successResponse([
            'books' => $bookResourceCollection,
        ]);
    }
}
