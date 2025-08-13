<?php

namespace App\Http\Controllers;

use App\Interfaces\MessageServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MessageServiceInterface $messageService): JsonResponse
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(MessageServiceInterface $messageService): JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MessageServiceInterface $messageService, Request $request): JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MessageServiceInterface $messageService, Request $request, string $id): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        //
    }
}
