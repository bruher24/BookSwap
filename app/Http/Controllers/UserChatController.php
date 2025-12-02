<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserChatController extends Controller
{
    public function index(
        ChatServiceInterface $chatService,
        UserServiceInterface $userService,
        Request $request,
        string $user_id
    ): JsonResource {
        // TODO: вынести логику в сервис
        $recipient_id = $request->input('recipient');
        $user = $userService->get($user_id);

        if (!$user) {
            $errors = ['Ошибка при получении пользователя'];
            return new FailureResource(['errors' => $errors]);
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
                $errors = ['Ошибка при создании чата'];
                return new FailureResource(['errors' => $errors]);
            }

            $user->refresh();
            $chats = $userService->chats($user->id);
        }

        $chatResourceCollection = ChatResource::collection($chats);
        $data = ['chats' => $chatResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function messages(ChatServiceInterface $chatService, string $user_id, string $recipient_id): JsonResource
    {
        $chat = $chatService->byUsers($user_id, $recipient_id);

        if (!$chat) {
            $chat = $chatService->create([
                'first_user_id' => $user_id,
                'second_user_id' => $recipient_id,
            ]);
        }

        if (!$chat) {
            $errors = ['Ошибка при создании чата'];
            return new FailureResource(['errors' => $errors]);
        }

        $messages = $chatService->messages($chat->id);
        $messageResourceCollection = MessageResource::collection($messages);
        $data = [
            'messages' => $messageResourceCollection,
            'blocked_by' => $chat->blocked_by,
        ];

        return new SuccessResource(['data' => $data]);
    }

    public function unreadMessages(UserServiceInterface $userService, string $user_id): JsonResource
    {
        $messages = $userService->getUnreadMessages($user_id);

        if (!$messages) {
            $errors = ['Ошибка при получении сообщений'];
            return new FailureResource(['errors' => $errors]);
        }

        $messageResourceCollection = MessageResource::collection($messages);
        $data = ['messages' => $messageResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function send(
        StoreMessageRequest $request,
        ChatServiceInterface $chatService,
        string $user_id,
        string $recipient_id
    ): JsonResource {
        $body = $request->input('body');
        $message = $chatService->sendMessage($user_id, $recipient_id, $body);
        $messageResource = new MessageResource($message);

        if (!$message) {
            $errors = ['Ошибка при отправке сообщения'];
            return new FailureResource(['errors' => $errors]);
        }

        $data = ['message' => $messageResource];

        return new SuccessResource(['data' => $data]);
    }

    public function read(UserServiceInterface $userService, Request $request, string $userId): JsonResource
    {
        // TODO: сделать нормально
        $messagesToRead = $request->input('messages');
        $user = $userService->get($userId);

        if (!$user) {
            $errors = ['Ошибка при получении пользователя'];
            return new FailureResource(['errors' => $errors]);
        }

        $checked = $userService->readMessages($user->id, $messagesToRead);

        if (!$checked) {
            $errors = ['Ошибка при прочтении сообщений'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }
}
