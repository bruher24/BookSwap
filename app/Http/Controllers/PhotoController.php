<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\FailureResource;
use App\Interfaces\PhotoServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PhotoController extends Controller
{
    public function index(PhotoServiceInterface $photoService): JsonResponse
    {
        $photos = $photoService->getAll();
        $photoResourceCollection = PhotoResource::collection($photos);
        $data = ['photos' => $photoResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(PhotoServiceInterface $photoService, StorePhotoRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $photo = $photoService->create($validated);

        if (!$photo) {
            $errors = ['Ошибка при создании фото'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $photoResource = new PhotoResource($photo);
        $data = ['photo' => $photoResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        $photo = $photoService->get($id);

        if (!$photo) {
            $errors = ['Ошибка при получении фото'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $photoResource = new PhotoResource($photo);
        $data = ['photo' => $photoResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(PhotoServiceInterface $photoService, UpdatePhotoRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        $photo = $photoService->update($id, $validated);

        if (!$photo) {
            $errors = ['Ошибка при обновлении фото'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $photoResource = new PhotoResource($photo);
        $data = ['photo' => $photoResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        if (!$photoService->delete($id)) {
            $errors = ['Ошибка при удалении фото'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
