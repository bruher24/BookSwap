<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\MessageServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MessageServiceInterface $messageService): JsonResponse
    {
        $covers = $messageService->getAll();
        return ResponseHelper::successResponse('Success', [
            'covers' => $covers,
        ]);
    }

    public function create(MessageServiceInterface $messageService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(MessageServiceInterface $messageService): JsonResponse
    {
        $validated = $request->validated();
        $cover = $messageService->create($validated);
        if (!$cover) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'cover' => $cover,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        $cover = $messageService->get($id);
        if (!$cover) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'cover' => $cover,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        $validated = $request->validated();
        $updated = $messageService->update($id, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        $deleted = $messageService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
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
