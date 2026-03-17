<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\SettingServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SettingController extends Controller
{
    public function index(SettingServiceInterface $settingService): JsonResponse
    {
        $settings = $settingService->getAll();
        $settingResourceCollection = SettingResource::collection($settings);
        $data = ['settings' => $settingResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(SettingServiceInterface $settingService, StoreSettingRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $setting = $settingService->create($validated);

        if (!$setting) {
            $errors = ['Ошибка при создании настройки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $settingResource = new SettingResource($setting);
        $data = ['setting' => $settingResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        $setting = $settingService->get($id);

        if (!$setting) {
            $errors = ['Ошибка при получении настройки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $settingResource = new SettingResource($setting);
        $data = ['setting' => $settingResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(
        SettingServiceInterface $settingService,
        UpdateSettingRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        $setting = $settingService->update($id, $validated);

        if (!$setting) {
            $errors = ['Ошибка при обновлении настройки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $settingResource = new SettingResource($setting);
        $data = ['setting' => $settingResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        if (!$settingService->delete($id)) {
            $errors = ['Ошибка при удалении настройки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
