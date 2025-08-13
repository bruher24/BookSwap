<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\AuthorServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(AuthorServiceInterface $authorService): JsonResponse
    {
        $authors = $authorService->getAll();
        return ResponseHelper::successResponse('Success', [
            'authors' => $authors
        ]);
//        return view('authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AuthorServiceInterface $authorService, int $author): JsonResponse
    {
        $author = $authorService->get($author);
        return ResponseHelper::successResponse('Success', [
            'author' => $author
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        //
    }

//    public function books(Request $request, BookServiceInterface $bookService, Author $author): JsonResponse
//    {
//        $filters = $bookService->getFilterFromRequest($request);
//
//        [$books, $params] = $bookService->byAuthor($author, $filters);
//
//        return response()->json($books);
//    }
}
