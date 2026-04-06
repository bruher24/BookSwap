<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class AuthController extends Controller
{
    public function register(UserServiceInterface $userService, AuthServiceInterface $authService, StoreUserRequest $request): JsonResponse
    {
        if (auth()->check()){
            $errors = ['Аутентифицированный пользователь не может регистрироваться'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();
        $user = $userService->create($validated);

        if (!$user) {
            $errors = ['Ошибка при регистрации'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $token = $authService->refreshToken($user->email);
        $data = [
            'user' => new UserResource($user),
            'token' => $token
        ];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function login(UserServiceInterface $userService, AuthServiceInterface $authService, AuthRequest $request): JsonResponse
    {
        if (auth()->check()){
            $errors = ['Аутентифицированный пользователь не может аутентифицироваться'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();

        if (!$authService->login($validated)) {
            $errors = ['Ошибка аутентификации'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $token = $authService->refreshToken($validated['email']);

        if (empty($token)) {
            $errors = ['Ошибка получения токена'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $user = $userService->where('email', $validated['email'])->first();
        $data = [
            'user' => new UserResource($user),
            'token' => $token
        ];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function refresh(UserServiceInterface $userService, AuthServiceInterface $authService, Request $request): JsonResponse
    {
        $email = $request->input('email');
        $user = $userService->where('email', $email)->first();

        Gate::authorize('refresh', $user);

        $token = $authService->refreshToken($email);

        if (empty($token)) {
            $errors = ['Ошибка обновления токена'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['token' => $token];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function logout(UserServiceInterface $userService, AuthServiceInterface $authService, Request $request): JsonResponse
    {
        $email = $request->input('email');
        $user = $userService->where('email', $email)->first();

        Gate::authorize('logout', $user);

        if (!$authService->logout($email)) {
            $errors = ['Ошибка при выходе из аккаунта'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
