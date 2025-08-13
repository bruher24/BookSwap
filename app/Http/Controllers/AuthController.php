<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function auth(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::validate($credentials)) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка авторизации',
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return ResponseHelper::successResponse('Успешный вход', [
            'token' => $token,
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        if (!Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ], $request->input('remember'))) {
            return back()->with('error', 'Введена неправильная комбинация email и пароля.');
        }
        $request->session()->regenerate();

        return redirect()->intended()->with('success', 'Добро пожаловать!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::user()->tokens()->delete();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Вы успешно вышли из аккаунта.');
    }
}
