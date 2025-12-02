<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\SettingServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class SettingController extends Controller
{
    public function index(SettingServiceInterface $settingService): JsonResource
    {
        $settings = $settingService->getAll();
        $settingResourceCollection = SettingResource::collection($settings);
        $data = ['settings' => $settingResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function store(SettingServiceInterface $settingService, StoreSettingRequest $request): JsonResource
    {
        $validated = $request->validated();
        $setting = $settingService->create($validated);

        if (!$setting) {
            $errors = ['Ошибка при создании настройки'];
            return new FailureResource(['errors' => $errors]);
        }

        $settingResource = new SettingResource($setting);
        $data = ['setting' => $settingResource];

        return new SuccessResource(['data' => $data]);
    }

    public function show(SettingServiceInterface $settingService, string $id): JsonResource
    {
        $setting = $settingService->get($id);

        if (!$setting) {
            $errors = ['Ошибка при получении настройки'];
            return new FailureResource(['errors' => $errors]);
        }

        $settingResource = new SettingResource($setting);
        $data = ['setting' => $settingResource];

        return new SuccessResource(['data' => $data]);
    }

    public function update(
        SettingServiceInterface $settingService,
        UpdateSettingRequest $request,
        string $id
    ): JsonResource {
        $validated = $request->validated();

        if (!$settingService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении настройки'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }

    public function destroy(SettingServiceInterface $settingService, string $id): JsonResource
    {
        if (!$settingService->delete($id)) {
            $errors = ['Ошибка при удалении настройки'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }
}
