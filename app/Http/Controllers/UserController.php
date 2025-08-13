<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function store(StoreUserRequest $request, UserServiceInterface $userService): RedirectResponse
    {
        $validated = $request->validated();
        $remember = $request->input('remember');
        if (!$userService->create($validated)) {
            return back()->with('error', 'Ошибка при создании пользователя.');
        }

        if (!Auth::attempt($validated, $remember)) {
            return back()->with('error', 'Ошибка при авторизации.')->onlyInput('email');
        }
        $request->session()->regenerate();
        return redirect()->intended()->with('success', 'Вы успешо зарегистрировались!');
    }

    public function update(UpdateUserRequest $request, UserServiceInterface $userService, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if ($userService->update($user, $validated)) {
            return redirect()->back()->with('success', 'Данные успешно обновлены!');
        }
        return back()->with('error', 'Ошибка обновления данных');
    }

    public function updateSettings(
        UpdateSettingRequest $request,
        UserServiceInterface $userService,
        User $user
    ): RedirectResponse {
        $validated = $request->validated();
        if (!$userService->updateSettings($user, $validated)) {
            return redirect()->back()->with('error', 'Ошибка сохранения настроек');
        }
        return redirect()->back()->with('success', 'Настройки успешно сохранены!');
    }

    public function books(Request $request, BookServiceInterface $bookService, User $user): View
    {
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byUser($user, $filters);

        return view('users.books', compact('books', 'params', 'filters'));
    }

    public function profile(string $section = 'personal'): View
    {
        $user = Auth::user();
        $userSettings = $user->settings ?? [];
        $settings = Setting::all();

        return view("profile.$section", compact('section', 'userSettings', 'settings'));
    }

    public function updateFavorites(UserServiceInterface $userService, Request $request, User $user): RedirectResponse
    {
        $book_id = $request->input('book_id');
        $isLiked = $request->input('isLiked');

        if (!$isLiked) {
            $userService->addToFavorites($user->id, $book_id);
        } else {
            $userService->removeFromFavorites($user->id, $book_id);
        }

        return redirect()->back();
    }

    public function chat(Request $request, UserServiceInterface $userService, User $user): View
    {
        $recipient = $request->input('recipient');
        $chats = $userService->getUserChats($user);

        if (isset($recipient) && $chats->where('first_user_id', $recipient)->isEmpty()
            && $chats->where('second_user_id', $recipient)->isEmpty()) {
            $arr = [$recipient, $user->id];
            sort($arr);
            $user->chats()->create([
                'first_user_id' => $arr[0],
                'second_user_id' => $arr[1],
            ]);
            $user->refresh();
            $chats = $userService->getUserChats($user);
        }

        return view('chat.index', compact('chats', 'recipient'));
    }

    public function notifications(UserServiceInterface $userService, User $user): View
    {
        $notifications = $userService->getUserNotifications($user);
        return view('notifications.index', compact('notifications'));
    }

    public function checkOne(UserServiceInterface $userService, User $user, Notification $notification): JsonResponse
    {
        $checked = $userService->checkOneNotification($user, $notification->id);
        if (!$checked) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении уведомлений',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    public function checkMany(Request $request, UserServiceInterface $userService, User $user): JsonResponse
    {
        $notifications = $request->input('notifications');
        if (isset($notifications)) {
            $checked = $userService->checkManyNotifications($user, $notifications);
        } else {
            $checked = $userService->checkAllNotifications($user);
        }
        if (!$checked) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении уведомлений',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    public function getUnreadMessages(UserServiceInterface $userService, User $user): JsonResponse
    {
        $messages = $userService->getUnreadMessages($user);
        return ResponseHelper::successResponse('Success', [
            'messages' => $messages,
        ]);
    }

    public function readMessages(Request $request, UserServiceInterface $userService, User $user): JsonResponse
    {
        $messagesToRead = $request->input('messages');
        $checked = $userService->readMessages($user, $messagesToRead);
        if (!$checked) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении сообщений',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }
}
