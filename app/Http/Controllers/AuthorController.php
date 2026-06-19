<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
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
        $data = ['authors' => AuthorResource::collection($authors)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(AuthorServiceInterface $authorService, StoreAuthorRequest $request): JsonResponse
    {
        Gate::authorize('create', Author::class);

        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $author = $authorService->create($validated);

        if (!$author) {
            $errors = ['Ошибка при создании автора'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['author' => new AuthorResource($author)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Author $author): JsonResponse
    {
        $data = ['author' => new AuthorResource($author)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(AuthorServiceInterface $authorService, UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        Gate::authorize('update', $author);

        $validated = $request->validated();
        $author = $authorService->update($author, $validated);

        if (!$author) {
            $errors = ['Ошибка при обновлении автора'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['author' => new AuthorResource($author)];
        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(AuthorServiceInterface $authorService, string $authorId): JsonResponse
    {
        $author = $authorService->get($authorId);

        if (!$author) {
            return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $author);

        if (!$authorService->delete($author)) {
            $errors = ['Ошибка при удалении автора'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
