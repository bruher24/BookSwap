<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function getMessages(UserServiceInterface $userService, User $user, User $recipient): JsonResponse
    {
        $chat = $userService->getChat($user, $recipient);
        $grouped = $userService->groupMessages($chat->messages()->orderBy('created_at')->orderBy('id')->get());

        return ResponseHelper::successResponse('Success', [
            'messages' => $grouped,
            'recipient' => $recipient,
            'is_blocked' => $chat->is_blocked,
        ]);
    }

    public function sendMessage(
        Request $request,
        UserServiceInterface $userService,
        User $user,
        User $recipient
    ): JsonResponse {
        $body = $request->input('body');
        $message = $userService->sendMessage($user, $recipient, $body);
        if (!$message) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при отправке сообщения',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'message' => $message,
        ]);
    }
}
