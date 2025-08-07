<?php

namespace App\Http\Controllers;

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

        return response()->json([
            'success' => true,
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
        return $userService->sendMessage($user, $recipient, $body);
    }
}
