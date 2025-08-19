<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\ChatResource;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserChatsController extends Controller
{
    public function index(
        ChatServiceInterface $chatService,
        UserServiceInterface $userService,
        Request $request,
        string $user_id
    ): JsonResponse {
        // TODO: сделать нормально
        $recipient_id = $request->input('recipient');
        $user = $userService->get($user_id);
        if (!$user) {
            return ResponseHelper::errorResponse(['Ошибка при получении пользователя']);
        }
        $chats = $userService->chats($user);

        if (isset($recipient_id)
            && $chats->where('first_user_id', $recipient_id)->isEmpty()
            && $chats->where('second_user_id', $recipient_id)->isEmpty()) {
            $arr = [$user_id, $recipient_id];
            sort($arr);
            if (!$chatService->create([
                'first_user_id' => $arr[0],
                'second_user_id' => $arr[1],
            ])) {
                return ResponseHelper::errorResponse(['Ошибка при создании чата']);
            }
            $user->refresh();
            $chats = $userService->chats($user);
        }

        $chatResourceCollection = ChatResource::collection($chats);

        return ResponseHelper::successResponse([
            'chats' => $chatResourceCollection,
        ]);
    }

    public function messages(UserServiceInterface $userService, string $user_id, string $recipient_id): JsonResponse
    {
        // TODO: добавить JsonResource
        // TODO: вынести в сервис
        $chat = $userService->getChat($user, $recipient);
        $grouped = $userService->groupMessages($chat->messages()->orderBy('created_at')->orderBy('id')->get());

        return ResponseHelper::successResponse([
            'messages' => $grouped,
            'recipient' => $recipient,
            'is_blocked' => $chat->is_blocked,
        ]);
    }

    public function unreadMessages(UserServiceInterface $userService, string $id): JsonResponse
    {
        $user = $userService->get($id);
        $messages = $userService->getUnreadMessages($user);
        return ResponseHelper::successResponse([
            'messages' => $messages,
        ]);
    }

    public function send(
        Request $request,
        UserServiceInterface $userService,
        string $user_id,
        string $recipient_id
    ): JsonResponse {
        $body = $request->input('body');
        $message = $userService->sendMessage($user, $recipient, $body);
        $messageResource = new MessageResource($message);
        if (!$message) {
            return ResponseHelper::errorResponse(['Ошибка при отправке сообщения']);
        }
        return ResponseHelper::successResponse([
            'message' => $messageResource,
        ], 'Сообщение успешно отправлено');
    }

    public function read(UserServiceInterface $userService, Request $request, string $id): JsonResponse
    {
        // TODO: сделать нормально
        $messagesToRead = $request->input('messages');
        $user = $userService->get($id);
        $checked = $userService->readMessages($user, $messagesToRead);
        if (!$checked) {
            return ResponseHelper::errorResponse(['Ошибка при прочтении сообщений']);
        }
        return ResponseHelper::successResponse();
    }
}
