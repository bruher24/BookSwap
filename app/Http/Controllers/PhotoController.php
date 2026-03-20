<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\FailureResource;
use App\Interfaces\PhotoServiceInterface;
use App\Models\Photo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class PhotoController extends Controller
{
    public function index(PhotoServiceInterface $photoService): JsonResponse
    {
        Gate::authorize('viewAny', $photoService);

        $photos = $photoService->getAll();
        $data = ['photos' => PhotoResource::collection($photos)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(PhotoServiceInterface $photoService, StorePhotoRequest $request): JsonResponse
    {
        Gate::authorize('create', Photo::class);

        $validated = $request->validated();
        $photo = $photoService->create($validated);

        if (!$photo) {
            $errors = ['Ошибка при создании фото'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['photo' => new PhotoResource($photo)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Photo $photo): JsonResponse
    {
        Gate::authorize('view', $photo);

        $data = ['photo' => new PhotoResource($photo)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(PhotoServiceInterface $photoService, UpdatePhotoRequest $request, Photo $photo): JsonResponse
    {
        Gate::authorize('update', $photo);

        $validated = $request->validated();
        $photo = $photoService->update($photo, $validated);

        if (!$photo) {
            $errors = ['Ошибка при обновлении фото'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['photo' => new PhotoResource($photo)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(PhotoServiceInterface $photoService, string $photoId): JsonResponse
    {
        $photo = $photoService->get($photoId);

        if (!$photo) {
            return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $photo);

        if (!$photoService->delete($photo)) {
            $errors = ['Ошибка при удалении фото'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
