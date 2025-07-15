<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\BookService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class UserController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(): View
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

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        if ($this->userService->update($user, $validated)) {
            // TODO: return redirect
        }
        // TODO: return redirect
    }

    public function books(Request $request, BookService $bookService, User $user): View|RedirectResponse
    {
        $filters = $this->userService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byUser($user, $filters);

        return view('users.books', compact('books', 'params', 'filters'));
    }

    public function profile(string $section = 'personal'): View
    {
        return view("profile.$section", compact('section'));
    }
}
