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

    /**
     * Show the form for creating a new resource.
     */
    public function create(AuthorServiceInterface $authorService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        $author = $authorService->get($id);
        return ResponseHelper::successResponse('Success', [
            'author' => $author
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Update the specified resource in storage.
     */
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
        return ResponseHelper::successResponse('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        $deleted = $authorService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении автора'
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    public function books(Request $request, BookServiceInterface $bookService, Author $author): JsonResponse
    {
        // TODO: исправить
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byAuthor($author, $filters);

        return response()->json($books);
    }
}
