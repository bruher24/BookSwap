<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Interfaces\AuthorServiceInterface;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class AuthorController extends Controller
{
    public function index(AuthorServiceInterface $authorService): JsonResponse
    {
        $authors = $authorService->getAll();

        return AuthorResource::collection($authors)->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @psalm-suppress PossiblyNullPropertyFetch
     */
    public function store(AuthorServiceInterface $authorService, StoreAuthorRequest $request): JsonResponse
    {
        Gate::authorize('create', Author::class);

        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $author = $authorService->create($validated);

        if (!$author) {
            return $this->errorResponse('Ошибка при создании автора', Response::HTTP_BAD_REQUEST);
        }

        return (new AuthorResource($author))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Author $author): JsonResponse
    {
        return (new AuthorResource($author))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(AuthorServiceInterface $authorService, UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        Gate::authorize('update', $author);

        $validated = $request->validated();
        $author = $authorService->update($author, $validated);

        if (!$author) {
            return $this->errorResponse('Ошибка при обновлении автора', Response::HTTP_BAD_REQUEST);
        }

        return (new AuthorResource($author))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(AuthorServiceInterface $authorService, int $authorId): JsonResponse
    {
        $author = $authorService->get($authorId);

        if (!$author) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $author);

        if (!$authorService->delete($author)) {
            return $this->errorResponse('Ошибка при удалении автора', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
