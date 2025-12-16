<?php

namespace App\Http\Controllers;

use App\Events\AuthorCreated;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\AuthorServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redis;

final class AuthorController extends Controller
{
    public function index(AuthorServiceInterface $authorService): JsonResource
    {
        $authors = $authorService->getAll();
        $authorResourceCollection = AuthorResource::collection($authors);

        Redis::publish('listeners', 'TEST MESSAGE FROM LARAVEL');
        AuthorCreated::dispatch('TEST MSG');

        return new SuccessResource(['authors' => $authorResourceCollection]);
    }

    public function store(AuthorServiceInterface $authorService, StoreAuthorRequest $request): JsonResource
    {
        $validated = $request->validated();
        $author = $authorService->create($validated);

        if (!$author) {
            $errors = ['Ошибка при создании автора'];
            return new FailureResource(['errors' => $errors]);
        }

        $authorResource = new AuthorResource($author);

        return new SuccessResource(['author' => $authorResource]);
    }

    public function show(AuthorServiceInterface $authorService, string $id): JsonResource
    {
        $author = $authorService->get($id);

        if (!$author) {
            $errors = ['Ошибка при получении автора'];
            return new FailureResource(['errors' => $errors]);
        }

        $authorResource = new AuthorResource($author);

        return new SuccessResource(['author' => $authorResource]);
    }

    public function update(
        AuthorServiceInterface $authorService,
        UpdateAuthorRequest $request,
        string $id
    ): JsonResource {
        $validated = $request->validated();

        if (!$authorService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении автора'];
            return new FailureResource(['errors' => $errors]);
        }

        // TODO: возвращать обновленный ресурс после PUT
        return new SuccessResource([]);
    }

    public function destroy(AuthorServiceInterface $authorService, string $id): JsonResource
    {
        if (!$authorService->delete($id)) {
            $errors = ['Ошибка при удалении автора'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }
}
