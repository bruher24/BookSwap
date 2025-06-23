<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function create(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $remember = $request->input('remember');
        if (!$this->userService->create($validated)) {
            return back()->withErrors([
                'error' => 'Ошибка при создании пользователя'
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

    public function update(StoreUserRequest $request, int $userId): RedirectResponse
    {
        $validated = $request->validated();
        if ($this->userService->update($userId, $validated)) {
            // TODO: return redirect
        }
        // TODO: return redirect
    }

    public function show($id) // TODO: return type
    {
        $user = $this->userService->get($id);
        if (!isset($user)) {
            return back()->withErrors(['error' => 'Пользователь не найден']);
        }
        return view('users.show', compact('user'));
    }
}
