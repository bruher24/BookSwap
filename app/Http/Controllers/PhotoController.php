<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\FailureResource;
use App\Interfaces\PhotoServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class PhotoController extends Controller
{
    public function index(PhotoServiceInterface $photoService): JsonResource
    {
        $photos = $photoService->getAll();
        $photoResourceCollection = PhotoResource::collection($photos);
        $data = ['photos' => $photoResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function store(PhotoServiceInterface $photoService, StorePhotoRequest $request): JsonResource
    {
        $validated = $request->validated();
        $photo = $photoService->create($validated);

        if (!$photo) {
            $errors = ['Ошибка при создании фото'];
            return new FailureResource(['errors' => $errors]);
        }

        $photoResource = new PhotoResource($photo);
        $data = ['photo' => $photoResource];

        return new SuccessResource(['data' => $data]);
    }

    public function show(PhotoServiceInterface $photoService, string $id): JsonResource
    {
        $photo = $photoService->get($id);

        if (!$photo) {
            $errors = ['Ошибка при получении фото'];
            return new FailureResource(['errors' => $errors]);
        }

        $photoResource = new PhotoResource($photo);
        $data = ['photo' => $photoResource];

        return new SuccessResource(['data' => $data]);
    }

    public function update(PhotoServiceInterface $photoService, UpdatePhotoRequest $request, string $id): JsonResource
    {
        $validated = $request->validated();

        if (!$photoService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении фото'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }

    public function destroy(PhotoServiceInterface $photoService, string $id): JsonResource
    {
        if (!$photoService->delete($id)) {
            $errors = ['Ошибка при удалении фото'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }
}
