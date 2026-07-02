<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

final class AdminAuthController extends Controller
{
    public function login(AuthRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $remember = $validated['remember'] ?? false;

        $user = User::where('email', $validated['email'])
            ->withoutTrashed()
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->errorResponse('Неправильный логин или пароль', Response::HTTP_UNAUTHORIZED);
        }

        if (!$user->isAdmin()) {
            return $this->errorResponse('Недостаточно прав', Response::HTTP_FORBIDDEN);
        }

        Auth::login($user, !!$remember);
        $request->session()->regenerate();

        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return $this->errorResponse('Гость не может выйти', Response::HTTP_UNAUTHORIZED);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->successResponse();
    }

    public function me(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return $this->errorResponse('Гость не имеет профиля', Response::HTTP_UNAUTHORIZED);
        }

        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_OK);
    }
}
