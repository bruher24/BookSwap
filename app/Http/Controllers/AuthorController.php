<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Interfaces\AuthorServiceInterface;
use App\Interfaces\BookServiceInterface;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(AuthorServiceInterface $authorService): JsonResponse
    {
        $authors = $authorService->getAll();
        return ResponseHelper::successResponse('Success', [
            'authors' => $authors
        ]);
    }

    public function store(AuthorServiceInterface $authorService, StoreAuthorRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $author = $authorService->create($validated);
        if (!$author) {
            return ResponseHelper::errorResponse([
                'Ошибка при создании автора',
            ]);
        }
        return ResponseHelper::successResponse('Автор успешно создан', [
            'author' => $author
        ]);
    }

    public function show(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        $author = $authorService->get($id);
        return ResponseHelper::successResponse('Success', [
            'author' => $author
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
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении автора',
            ]);
        }
        return ResponseHelper::successResponse('Автор успешно обновлен');
    }

    public function destroy(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        $deleted = $authorService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении автора'
            ]);
        }
        return ResponseHelper::successResponse('Автор успешно удален');
    }

    public function books(Request $request, BookServiceInterface $bookService, Author $author): JsonResponse
    {
        // TODO: исправить
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byAuthor($author, $filters);

        return response()->json($books);
    }
}
