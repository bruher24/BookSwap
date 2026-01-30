<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoverRequest;
use App\Http\Requests\UpdateCoverRequest;
use App\Http\Resources\CoverResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\CoverServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class CoverController extends Controller
{
    public function index(CoverServiceInterface $coverService): JsonResource
    {
        $covers = $coverService->getAll();
        $coverResourceCollection = CoverResource::collection($covers);
        $data = ['covers' => $coverResourceCollection];

        return new SuccessResource($data);
    }

    public function store(CoverServiceInterface $coverService, StoreCoverRequest $request): JsonResource
    {
        $validated = $request->validated();
        $cover = $coverService->create($validated);

        if (!$cover) {
            $errors = ['Ошибка при создании обложки'];
            return new FailureResource($errors);
        }

        $coverResource = new CoverResource($cover);
        $data = ['cover' => $coverResource];

        return new SuccessResource($data);
    }

    public function show(CoverServiceInterface $coverService, string $id): JsonResource
    {
        $cover = $coverService->get($id);

        if (!$cover) {
            $errors = ['Ошибка при получении обложки'];
            return new FailureResource($errors);
        }

        $coverResource = new CoverResource($cover);
        $data = ['cover' => $coverResource];

        return new SuccessResource($data);
    }

    public function update(CoverServiceInterface $coverService, UpdateCoverRequest $request, string $id): JsonResource
    {
        $validated = $request->validated();

        if (!$coverService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении обложки'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }

    public function destroy(CoverServiceInterface $coverService, string $id): JsonResource
    {
        if (!$coverService->delete($id)) {
            $errors = ['Ошибка при удалении обложки'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }
}
