<?php

namespace App\Http\Controllers;

use App\Events\AuthorCreated;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\AuthorServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

final class AuthorController extends Controller
{
    public function index(AuthorServiceInterface $authorService): JsonResponse
    {
        $authors = $authorService->getAll();
        $authorResourceCollection = AuthorResource::collection($authors);
        $data = ['authors' => $authorResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(AuthorServiceInterface $authorService, StoreAuthorRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $author = $authorService->create($validated);

        if (!$author) {
            $errors = ['Ошибка при создании автора'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $authorResource = new AuthorResource($author);
        $data = ['author' => $authorResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        $author = $authorService->get($id);

        if (!$author) {
            $errors = ['Ошибка при получении автора'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $authorResource = new AuthorResource($author);
        $data = ['author' => $authorResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(
        AuthorServiceInterface $authorService,
        UpdateAuthorRequest    $request,
        string                 $id
    ): JsonResponse
    {
        $validated = $request->validated();
        $author = $authorService->update($id, $validated);
        if (!$author) {
            $errors = ['Ошибка при обновлении автора'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $authorResource = new AuthorResource($author);
        $data = ['author' => $authorResource];
        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(AuthorServiceInterface $authorService, string $id): JsonResponse
    {
        if (!$authorService->delete($id)) {
            $errors = ['Ошибка при удалении автора'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
