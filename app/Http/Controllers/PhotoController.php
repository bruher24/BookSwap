<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Interfaces\PhotoServiceInterface;
use App\Models\Photo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class PhotoController extends Controller
{
    public function index(PhotoServiceInterface $photoService): JsonResponse
    {
        $photos = $photoService->getAll();

        return PhotoResource::collection($photos)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(PhotoServiceInterface $photoService, StorePhotoRequest $request): JsonResponse
    {
        Gate::authorize('create', Photo::class);

        $validated = $request->validated();
        $photo = $photoService->create($validated);

        if (!$photo) {
            return $this->errorResponse('Ошибка при создании фото', Response::HTTP_BAD_REQUEST);
        }

        return (new PhotoResource($photo))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Photo $photo): JsonResponse
    {
        return (new PhotoResource($photo))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(PhotoServiceInterface $photoService, int $photoId): JsonResponse
    {
        $photo = $photoService->get($photoId);

        if (!$photo) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        if (!$photoService->delete($photo)) {
            return $this->errorResponse('Ошибка при удалении фото', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
