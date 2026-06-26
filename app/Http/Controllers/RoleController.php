<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Interfaces\RoleServiceInterface;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class RoleController extends Controller
{
    public function index(RoleServiceInterface $roleService): JsonResponse
    {
        $roles = $roleService->getAll();

        return RoleResource::collection($roles)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(RoleServiceInterface $roleService, StoreRoleRequest $request): JsonResponse
    {
        Gate::authorize('create', Role::class);

        $validated = $request->validated();
        $role = $roleService->create($validated);

        if (!$role) {
            return $this->errorResponse('Ошибка при создании роли', Response::HTTP_BAD_REQUEST);
        }

        return (new RoleResource($role))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Role $role): JsonResponse
    {
        return (new RoleResource($role))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(RoleServiceInterface $roleService, UpdateRoleRequest $request, Role $role): JsonResponse
    {
        Gate::authorize('update', $role);

        $validated = $request->validated();
        $role = $roleService->update($role, $validated);

        if (!$role) {
            return $this->errorResponse('Ошибка при обновлении роли', Response::HTTP_BAD_REQUEST);
        }

        return (new RoleResource($role))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(RoleServiceInterface $roleService, int $roleId): JsonResponse
    {
        $role = $roleService->get($roleId);

        if (!$role) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $role);

        if (!$roleService->delete($role)) {
            return $this->errorResponse('Ошибка при удалении роли', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
