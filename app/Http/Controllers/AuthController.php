<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
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
            return $this->errorResponse('Аутентифицированный пользователь не может регистрироваться', Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();
        $user = $userService->create($validated);

        if (!$user) {
            return $this->errorResponse('Ошибка при регистрации', Response::HTTP_BAD_REQUEST);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function login(AuthRequest $request): JsonResponse
    {
        if (Auth::check()) {
            return $this->errorResponse('Пользователь уже аутентифицирован', Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validated();
        $remember = isset($validated['remember']) && $validated['remember'] == '1';
        unset($validated['remember']);

        if (!Auth::attempt($validated, $remember)) {
            return $this->errorResponse('Неправильный логин или пароль', Response::HTTP_UNAUTHORIZED);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return $this->errorResponse('Гость не может выйти', Response::HTTP_BAD_REQUEST);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->successResponse();
    }

    public function verifyEmail(Request $request, UserServiceInterface $userService, int $userId): RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            abort(Response::HTTP_BAD_REQUEST);
        }

        // TODO: заменить на реальные страницы фронта
        if (!$userService->verifyEmail($userId)) {
            return response()->redirectTo('http://localhost:5173/failure');
        }

        return response()->redirectTo('http://localhost:5173/success');
    }
}
