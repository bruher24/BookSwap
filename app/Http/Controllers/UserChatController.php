<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserChatController extends Controller
{
    public function index(
        ChatServiceInterface $chatService,
        UserServiceInterface $userService,
        Request $request,
        string $user_id
    ): JsonResponse {
        $recipient_id = $request->input('recipient');
        $user = $userService->get($user_id);
        if (!$user) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении пользователя']);
        }
        $chats = $userService->chats($user->id);

        if (isset($recipient_id)
            && $chats->where('first_user_id', $recipient_id)->isEmpty()
            && $chats->where('second_user_id', $recipient_id)->isEmpty()) {
            $arr = [$user_id, $recipient_id];
            sort($arr);
            if (!$chatService->create([
                'first_user_id' => $arr[0],
                'second_user_id' => $arr[1],
            ])) {
                return ResponseHelper::errorResponse(400, ['Ошибка при создании чата']);
            }
            $user->refresh();
            $chats = $userService->chats($user->id);
        }

        $chatResourceCollection = ChatResource::collection($chats);

        return ResponseHelper::successResponse([
            'chats' => $chatResourceCollection,
        ]);
    }

    public function messages(ChatServiceInterface $chatService, string $user_id, string $recipient_id): JsonResponse
    {
        $chat = $chatService->byUsers($user_id, $recipient_id);
        if (!$chat) {
            $chat = $chatService->create([
                'first_user_id' => $user_id,
                'second_user_id' => $recipient_id,
            ]);
        }

        if (!$chat) {
            return ResponseHelper::errorResponse(400, ['Ошибка при создании чата']);
        }

        $messages = $chatService->messages($chat->id);

        $messageResourceCollection = MessageResource::collection($messages);

        return ResponseHelper::successResponse([
            'messages' => $messageResourceCollection,
            'blocked_by' => $chat->blocked_by,
        ]);
    }

    public function unreadMessages(UserServiceInterface $userService, string $user_id): JsonResponse
    {
        $messages = $userService->getUnreadMessages($user_id);
        if (!$messages) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении сообщений']);
        }
        $messageResourceCollection = MessageResource::collection($messages);

        return ResponseHelper::successResponse([
            'messages' => $messageResourceCollection,
        ]);
    }

    public function send(
        StoreMessageRequest $request,
        ChatServiceInterface $chatService,
        string $user_id,
        string $recipient_id
    ): JsonResponse {
        $body = $request->input('body');
        $message = $chatService->sendMessage($user_id, $recipient_id, $body);
        $messageResource = new MessageResource($message);
        if (!$message) {
            return ResponseHelper::errorResponse(400, ['Ошибка при отправке сообщения']);
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

        if (!$user) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении пользователя']);
        }

        $checked = $userService->readMessages($user->id, $messagesToRead);

        if (!$checked) {
            return ResponseHelper::errorResponse(400, ['Ошибка при прочтении сообщений']);
        }

        return ResponseHelper::successResponse();
    }
}
