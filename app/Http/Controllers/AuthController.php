<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function auth(AuthServiceInterface $authService, Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $token = $authService->auth($credentials);

        if (empty($token)) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка авторизации',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'token' => $token,
        ]);
    }

    public function login(AuthServiceInterface $authService, Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password', 'remember');
        if (!$authService->login($credentials)) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка авторизации',
            ]);
        }
        $request->session()->regenerate();
        $user = $authService->currentUser();
        return ResponseHelper::successResponse('Успешная авторизация!', [
            'user' => $user,
        ]);
    }

    public function logout(AuthServiceInterface $authService, Request $request): JsonResponse
    {
        $authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return ResponseHelper::successResponse('До свидания!');
    }
}
