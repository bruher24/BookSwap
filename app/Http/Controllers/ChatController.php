<?php

namespace App\Http\Controllers;

use App\Events\MessageReceived;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;


class ChatController extends Controller
{
    // TODO: проверять блэклист получателя

    public function getMessages(UserServiceInterface $userService, User $user, User $recipient): JsonResponse
    {
        $messages = $userService->getMessages($user, $recipient);
        $grouped = $userService->groupMessages($messages);

        return response()->json([
            'success' => true,
            'messages' => $grouped,
            'recipient' => $recipient
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
