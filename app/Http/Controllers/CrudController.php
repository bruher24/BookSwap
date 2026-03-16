<?php

namespace App\Http\Controllers;

use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\ServiceInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

abstract class CrudController extends Controller
{
    protected ServiceInterface $service;
    protected string $resourceClass;
    protected string $resourceKey;
    protected string $resourceCollectionKey;
    protected string $storeRequestClass;
    protected string $updateRequestClass;
    protected string $createErrorMessage;
    protected string $getErrorMessage;
    protected string $updateErrorMessage;
    protected string $deleteErrorMessage;

    public function __construct(ServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $items = $this->service->getAll();
        $resourceCollection = $this->resourceClass::collection($items);
        $data = [$this->resourceCollectionKey => $resourceCollection];

        return $this->successResponse($data, $this->indexStatusCode($items));
    }

    public function store(): JsonResponse
    {
        try {
            $request = $this->resolveRequest($this->storeRequestClass);
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->failureResponse('Ошибка при обработке запроса', Response::HTTP_BAD_REQUEST);
        }

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!$model) {
            return $this->failureResponse($this->createErrorMessage, Response::HTTP_BAD_REQUEST);
        }

        $resource = new $this->resourceClass($model);
        $data = [$this->resourceKey => $resource];

        return $this->successResponse($data, $this->storeStatusCode());
    }

    public function show(string $id): JsonResponse
    {
        $model = $this->service->get($id);

        if (!$model) {
            return $this->failureResponse($this->getErrorMessage, Response::HTTP_NOT_FOUND);
        }

        $resource = new $this->resourceClass($model);
        $data = [$this->resourceKey => $resource];

        return $this->successResponse($data, Response::HTTP_OK);
    }

    public function update(string $id): JsonResponse
    {
        try {
            $request = $this->resolveRequest($this->updateRequestClass);
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->failureResponse('Ошибка при обработке запроса', Response::HTTP_BAD_REQUEST);
        }

        $validated = $request->validated();
        $model = $this->service->update($id, $validated);

        if (!$model) {
            return $this->failureResponse($this->updateErrorMessage, Response::HTTP_BAD_REQUEST);
        }

        $resource = new $this->resourceClass($model);
        $data = [$this->resourceKey => $resource];

        return $this->successResponse($data, Response::HTTP_OK);
    }

    public function destroy(string $id): JsonResponse
    {
        if (!$this->service->delete($id)) {
            return $this->failureResponse($this->deleteErrorMessage, Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse([], $this->destroyStatusCode());
    }

    protected function indexStatusCode(Collection $items): int
    {
        return Response::HTTP_OK;
    }

    protected function storeStatusCode(): int
    {
        return Response::HTTP_CREATED;
    }

    protected function destroyStatusCode(): int
    {
        return Response::HTTP_ACCEPTED;
    }

    protected function successResponse(array $data, int $status): JsonResponse
    {
        if (empty($data)) {
            return (new SuccessResource())
                ->response()
                ->setStatusCode($status);
        }

        return (new SuccessResource($data))
            ->response()
            ->setStatusCode($status);
    }

    protected function failureResponse(string $message, int $status): JsonResponse
    {
        return (new FailureResource([$message]))
            ->response()
            ->setStatusCode($status);
    }

    protected function resolveRequest(string $requestClass): FormRequest
    {
        $request = app($requestClass);

        if (!$request instanceof FormRequest) {
            throw new Exception("$requestClass must be a FormRequest");
        }

        return $request;
    }
}
