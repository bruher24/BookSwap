<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthorService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    private UserService $userService;
    private AuthorService $authorService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->authorService = new AuthorService();
    }

    public function index()
    {
        $users = $this->userService->getAll();
        // TODO: return view
//        return view('users.index', compact('users'));
    }

    public function create(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $remember = $request->input('remember');
        if (!$this->userService->create($validated)) {
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

    public function update(UpdateUserRequest $request, int $userId): RedirectResponse
    {
        $validated = $request->validated();
        if ($this->userService->update($userId, $validated)) {
            // TODO: return redirect
        }
        // TODO: return redirect
    }

    public function books(Request $request, int $userId): View|RedirectResponse
    {
        $filters = [];
        if ($request->isMethod('POST')) {
            $inputFilters = $request->except('_token');
            $filters = $this->userService->formatFilters($inputFilters);
        }
        $user = Auth::user() ?? $this->userService->get($userId);
        if (!$user) {
            return back()->withErrors(['error' => 'Пользователь не найден.']);
        }

        [$books, $params] = $this->userService->books($userId, $filters);

        return view('user.books', compact('books', 'params', 'filters'));
    }

    public function profile(string $section = 'personal'): View
    {
        return view("profile.$section", compact('section'));
    }
}
