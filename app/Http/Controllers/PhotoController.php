<?php

namespace App\Http\Controllers;

use App\Interfaces\PhotoServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PhotoServiceInterface $photoService): JsonResponse
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PhotoServiceInterface $photoService): JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PhotoServiceInterface $photoService, Request $request): JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PhotoServiceInterface $photoService, Request $request, string $id): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        //
    }
}
