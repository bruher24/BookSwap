<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function index(ChatServiceInterface $chatService): JsonResponse
    {
        $chats = $chatService->getAll();
        return ResponseHelper::successResponse('Success', [
            'chats' => $chats,
        ]);
    }

    public function create(ChatServiceInterface $chatService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(ChatServiceInterface $chatService): JsonResponse
    {
        $validated = $request->validated();
        $chat = $chatService->create($validated);
        if (!$chat) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании чата',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'chat' => $chat,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        $chat = $chatService->get($id);
        if (!$chat) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении чата',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'chat' => $chat,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        $validated = $request->validated();
        $updated = $chatService->update($id, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении чата',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        $deleted = $chatService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении чата',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    public function messages(UserServiceInterface $userService, User $user, User $recipient): JsonResponse
    {
        $chat = $userService->getChat($user, $recipient);
        $grouped = $userService->groupMessages($chat->messages()->orderBy('created_at')->orderBy('id')->get());

        return ResponseHelper::successResponse('Success', [
            'messages' => $grouped,
            'recipient' => $recipient,
            'is_blocked' => $chat->is_blocked,
        ]);
    }
}
