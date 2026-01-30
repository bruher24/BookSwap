<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AuthController extends Controller
{
    public function register(
        UserServiceInterface $userService,
        AuthServiceInterface $authService,
        StoreUserRequest $request
    ): JsonResource {
        $validated = $request->validated();
        $user = $userService->create($validated);

        if (!$user) {
            $errors = ['Ошибка при регистрации'];
            return new FailureResource($errors);
        }

        $token = $authService->refreshToken($user->id);
        $userResource = new UserResource($user);
        $data = [
            'user' => $userResource,
            'token' => $token
        ];

        return new SuccessResource($data);
    }

    public function login(
        UserServiceInterface $userService,
        AuthServiceInterface $authService,
        AuthRequest $request
    ): JsonResource {
        $validated = $request->validated();

        if (!$authService->login($validated)) {
            $errors = ['Ошибка аутентификации'];
            return new FailureResource($errors);
        }

        $token = $authService->refreshToken($validated['email']);

        if (empty($token)) {
            $errors = ['Ошибка получения токена'];
            return new FailureResource($errors);
        }

        $user = $userService->where('email', $validated['email'])->first();
        $userResource = new UserResource($user);
        $data = [
            'user' => $userResource,
            'token' => $token
        ];

        return new SuccessResource($data);
    }

    public function refresh(AuthServiceInterface $authService, Request $request): JsonResource
    {
        $email = $request->input('email');
        $token = $authService->refreshToken($email);

        if (empty($token)) {
            $errors = ['Ошибка обновления токена'];
            return new FailureResource($errors);
        }

        $data = ['token' => $token];

        return new SuccessResource($data);
    }

    public function logout(AuthServiceInterface $authService, Request $request): JsonResource
    {
        $email = $request->input('email');

        if (!$authService->logout($email)) {
            $errors = ['Ошибка при выходе из аккаунта'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }
}
