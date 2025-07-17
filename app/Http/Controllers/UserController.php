<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(UserServiceInterface $userService): View
    {
        $users = $userService->getAll();
        // TODO: return view
//        return view('users.index', compact('users'));
    }

    public function create(StoreUserRequest $request, UserServiceInterface $userService): RedirectResponse
    {
        $validated = $request->validated();
        $remember = $request->input('remember');
        if (!$userService->create($validated)) {
            return back()->withErrors([
                'error' => 'Ошибка при создании пользователя.'
            ]);
        }

        if (!Auth::attempt($validated, $remember)) {
            return back()->withErrors([
                'email' => 'Error.',
            ])->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->intended()->with('success', 'Вы успешо зарегистрировались!');
    }

    public function login(Request $request): RedirectResponse
    {
        if (!Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ], $request->input('remember'))) {
            return back()->withErrors([
                'Введена неправильная комбинация email и пароля.'
            ]);
        }
        $request->session()->regenerate();
        return redirect()->intended()->with('success', 'Добро пожаловать!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Вы успешно вышли из аккаунта.');
    }

    public function update(UpdateUserRequest $request, UserServiceInterface $userService, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if ($userService->update($user, $validated)) {
            return redirect()->back()->with('success', 'Данные успешно обновлены!');
        }
        return back()->withErrors(['Ошибка обновления данных']);
    }

    public function updateSettings(
        UpdateSettingRequest $request,
        UserServiceInterface $userService,
        User $user
    ): RedirectResponse {
        $validated = $request->validated();
        if (!$userService->updateSettings($user, $validated)) {
            return redirect()->back()->withErrors(['Ошибка сохранения настроек']);
        }
        return redirect()->back()->with('success', 'Настройки успешно сохранены!');
    }

    public function books(Request $request, BookServiceInterface $bookService, User $user): View|RedirectResponse
    {
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byUser($user, $filters);

        return view('users.books', compact('books', 'params', 'filters'));
    }

    public function profile(string $section = 'personal'): View
    {
        $userSettings = [];
        if ($section == 'settings') {
            $userSettings = Auth::user()->settings;
        }

        $settings = Setting::all();

        return view("profile.$section", compact('section', 'userSettings', 'settings'));
    }
}
