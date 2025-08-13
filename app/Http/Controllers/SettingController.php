<?php

namespace App\Http\Controllers;

use App\Interfaces\SettingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SettingServiceInterface $settingService): JsonResponse
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(SettingServiceInterface $settingService): JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingServiceInterface $settingService, Request $request): JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingServiceInterface $settingService, Request $request, string $id): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        //
    }
}
