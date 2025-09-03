<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\AuthRequest;
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

        $token = $authService->refreshToken($user->id);
        $userResource = new UserResource($user);

        return ResponseHelper::successResponse([
            'user' => $userResource,
            'token' => $token,
        ], 'Успешная регистрация');
    }

    public function login(
        UserServiceInterface $userService,
        AuthServiceInterface $authService,
        AuthRequest $request
    ): JsonResponse {
        $validated = $request->validated();

        if (!$authService->login($validated)) {
            return ResponseHelper::errorResponse(['Ошибка аутентификации']);
        }

        $token = $authService->refreshToken($validated['email']);
        if (empty($token)) {
            return ResponseHelper::errorResponse(['Ошибка получения токена']);
        }

        $user = $userService->where('email', $validated['email'])->first();
        $userResource = new UserResource($user);

        return ResponseHelper::successResponse([
            'user' => $userResource,
            'token' => $token,
        ], 'Успешная аутентификация');
    }

    public function refresh(AuthServiceInterface $authService, Request $request): JsonResponse
    {
        $email = $request->input('email');

        $token = $authService->refreshToken($email);
        if (empty($token)) {
            return ResponseHelper::errorResponse(['Ошибка обновления токена']);
        }

        return ResponseHelper::successResponse([
            'token' => $token,
        ]);
    }

    public function logout(AuthServiceInterface $authService, Request $request): JsonResponse
    {
        $email = $request->input('email');
        if (!$authService->logout($email)) {
            return ResponseHelper::errorResponse(['Ошибка при выходе из аккаунта']);
        }
        return ResponseHelper::successResponse([], 'До свидания');
    }
}
