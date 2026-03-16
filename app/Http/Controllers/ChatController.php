<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Resources\ChatResource;
use App\Interfaces\ChatServiceInterface;

final class ChatController extends CrudController
{
    public function __construct(ChatServiceInterface $chatService)
    {
        $this->resourceClass = ChatResource::class;
        $this->resourceKey = 'chat';
        $this->resourceCollectionKey = 'chats';
        $this->storeRequestClass = StoreChatRequest::class;
        $this->updateRequestClass = UpdateChatRequest::class;
        $this->createErrorMessage = 'Ошибка при создании чата';
        $this->getErrorMessage = 'Ошибка при получении чата';
        $this->updateErrorMessage = 'Ошибка при обновлении чата';
        $this->deleteErrorMessage = 'Ошибка при удалении чата';

        parent::__construct($chatService);
    }
}
