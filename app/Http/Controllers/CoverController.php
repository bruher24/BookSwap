<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoverRequest;
use App\Http\Requests\UpdateCoverRequest;
use App\Http\Resources\CoverResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\CoverServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CoverController extends Controller
{
    public function index(CoverServiceInterface $coverService): JsonResponse
    {
        $covers = $coverService->getAll();
        $coverResourceCollection = CoverResource::collection($covers);
        $data = ['covers' => $coverResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(CoverServiceInterface $coverService, StoreCoverRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $cover = $coverService->create($validated);

        if (!$cover) {
            $errors = ['Ошибка при создании обложки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $coverResource = new CoverResource($cover);
        $data = ['cover' => $coverResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        $cover = $coverService->get($id);

        if (!$cover) {
            $errors = ['Ошибка при получении обложки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $coverResource = new CoverResource($cover);
        $data = ['cover' => $coverResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(CoverServiceInterface $coverService, UpdateCoverRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        $cover = $coverService->update($id, $validated);

        if (!$cover) {
            $errors = ['Ошибка при обновлении обложки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $coverResource = new CoverResource($cover);
        $data = ['cover' => $coverResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        if (!$coverService->delete($id)) {
            $errors = ['Ошибка при удалении обложки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
