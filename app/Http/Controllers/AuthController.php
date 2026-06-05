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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class AuthController extends Controller
{
    public function register(UserServiceInterface $userService, StoreUserRequest $request): JsonResponse
    {
        if (Auth::check()) {
            $errors = ['Аутентифицированный пользователь не может регистрироваться'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();
        $user = $userService->create($validated);

        if (!$user) {
            $errors = ['Ошибка при регистрации'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $data = [
            'user' => new UserResource($user)
        ];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function login(AuthRequest $request): JsonResponse
    {
        if (Auth::check()) {
            $errors = ['Пользователь уже аутентифицирован'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();
        $remember = $validated['remember'] ?? false;
        unset($validated['remember']);

        if (!Auth::attempt($validated, $remember)) {
            $errors = ['Неправильный логин или пароль'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $request->session()->regenerate();
        $user = Auth::user();
        $data = [
            'user' => new UserResource($user)
        ];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            $errors = ['Гость не может выйти'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function verifyEmail(Request $request, AuthServiceInterface $authService): RedirectResponse
    {
        $userId = $request->route('userId') ?? null;

        if (!$request->hasValidSignature() || !isset($userId)) {
            abort(Response::HTTP_BAD_REQUEST);
        }

        // TODO: заменить на реальные страницы фронта
        if (!$authService->verifyEmail($userId)) {
            return response()->redirectTo('http://localhost:5173/failure');
        }

        return response()->redirectTo('http://localhost:5173/success');
    }
}
