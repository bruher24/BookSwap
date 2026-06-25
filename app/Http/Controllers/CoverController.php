<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoverRequest;
use App\Http\Resources\CoverResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\CoverServiceInterface;
use App\Models\Cover;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class CoverController extends Controller
{
    public function index(CoverServiceInterface $coverService): JsonResponse
    {
        Gate::authorize('viewAny', Cover::class);

        $covers = $coverService->getAll();
        $data = ['covers' => CoverResource::collection($covers)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(CoverServiceInterface $coverService, StoreCoverRequest $request): JsonResponse
    {
        Gate::authorize('create', Cover::class);

        $validated = $request->validated();
        $cover = $coverService->create($validated);

        if (!$cover) {
            $errors = ['Ошибка при создании обложки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['cover' => new CoverResource($cover)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Cover $cover): JsonResponse
    {
        $data = ['cover' => new CoverResource($cover)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(CoverServiceInterface $coverService, int $coverId): JsonResponse
    {
        $cover = $coverService->get($coverId);

        if (!$cover) {
            return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $cover);

        if (!$coverService->delete($cover)) {
            $errors = ['Ошибка при удалении обложки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
