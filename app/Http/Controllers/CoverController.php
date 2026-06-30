<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoverRequest;
use App\Http\Resources\CoverResource;
use App\Interfaces\CoverServiceInterface;
use App\Models\Cover;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class CoverController extends Controller
{
    public function index(CoverServiceInterface $coverService): JsonResponse
    {
        $covers = $coverService->getAll();

        return CoverResource::collection($covers)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(CoverServiceInterface $coverService, StoreCoverRequest $request): JsonResponse
    {
        Gate::authorize('create', Cover::class);

        $validated = $request->validated();
        $cover = $coverService->create($validated);

        if (!$cover) {
            return $this->errorResponse('Ошибка при создании обложки', Response::HTTP_BAD_REQUEST);
        }

        return (new CoverResource($cover))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Cover $cover): JsonResponse
    {
        return (new CoverResource($cover))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(CoverServiceInterface $coverService, int $coverId): JsonResponse
    {
        $cover = $coverService->get($coverId);

        if (!$cover) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $cover);

        if (!$coverService->delete($cover)) {
            return $this->errorResponse('Ошибка при удалении обложки', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
