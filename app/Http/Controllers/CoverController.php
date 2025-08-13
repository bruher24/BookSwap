<?php

namespace App\Http\Controllers;

use App\Interfaces\CoverServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CoverServiceInterface $coverService): JsonResponse
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CoverServiceInterface $coverService): JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CoverServiceInterface $coverService, Request $request): JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CoverServiceInterface $coverService, Request $request, string $id): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        //
    }
}
