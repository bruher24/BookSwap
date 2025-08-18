<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(
        UserServiceInterface $userService,
        AuthServiceInterface $authService,
        StoreUserRequest $request
    ): JsonResponse {
        $validated = $request->validated();
        $user = $userService->create($validated);
        if (!$user) {
            return ResponseHelper::errorResponse(['Ошибка при регистрации']);
        }
        $token = $authService->refreshToken($user);
        $userResource = new UserResource($user);
        return ResponseHelper::successResponse([
            'user' => $userResource,
            'token' => $token,
        ], 'Успешная регистрация');
    }

    public function login(AuthServiceInterface $authService, Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password', 'remember');
        if (!$authService->login($credentials)) {
            return ResponseHelper::errorResponse(['Ошибка аутентификации']);
        }
        $user = $authService->currentUser();
        $token = $authService->refreshToken($user);
        if (empty($token)) {
            return ResponseHelper::errorResponse(['Ошибка получения токена']);
        }
        session()->regenerate();
        $user = $authService->currentUser();
        $userResource = new UserResource($user);
        return ResponseHelper::successResponse([
            'user' => $userResource,
            'token' => $token,
        ], 'Успешная аутентификация');
    }

    public function logout(AuthServiceInterface $authService): JsonResponse
    {
        if (!$authService->logout()) {
            return ResponseHelper::errorResponse(['Ошибка при выходе из аккаунта']);
        }
        session()->invalidate();
        session()->regenerateToken();
        return ResponseHelper::successResponse([], 'До свидания');
    }

    public function refresh(AuthServiceInterface $authService): JsonResponse
    {
        $user = $authService->currentUser();
        $token = $authService->refreshToken($user);

        if (empty($token)) {
            return ResponseHelper::errorResponse(['Ошибка обновления токена']);
        }
        return ResponseHelper::successResponse([
            'token' => $token,
        ]);
    }
}
