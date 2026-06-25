<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
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
            $errors = ['Неправильный логин или пароль'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        if (!$user->isAdmin()) {
            $errors = ['Недостаточно прав'];
            return (new FailureResource([$errors]))->response()->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        Auth::login($user, !!$remember);
        $request->session()->regenerate();

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            $errors = ['Гость не может выйти'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }

    public function profile(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            $errors = ['Гость не может получить профиль'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        return (new SuccessResource([$user]))->response()->setStatusCode(Response::HTTP_OK);
    }
}
