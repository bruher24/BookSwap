<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\NotificationServiceInterface;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(NotificationServiceInterface $notificationService): JsonResponse
    {
        $notifications = $notificationService->getAll() ?? Notification::all();

        return ResponseHelper::successResponse('Success', [
            'notifications' => $notifications
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(NotificationServiceInterface $notificationService): JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NotificationServiceInterface $notificationService, Request $request): JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        $notification = $notificationService->get($id);

        return ResponseHelper::successResponse('Success', [
            'notification' => $notification
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        NotificationServiceInterface $notificationService,
        Request $request,
        string $id
    ): JsonResponse {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        //
    }
}
