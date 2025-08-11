<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\AuthorServiceInterface;
use App\Interfaces\BookServiceInterface;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function index(AuthorServiceInterface $authorService): View
    {
        $authors = $authorService->getAll();
        return view('authors.index', compact('authors'));
    }

    public function getAuthors(AuthorServiceInterface $authorService): JsonResponse
    {
        return ResponseHelper::successResponse('Success', [
            'authors' => $authorService->getAll(),
        ]);
    }

    public function books(Request $request, BookServiceInterface $bookService, Author $author): View
    {
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byAuthor($author, $filters);

        return view('authors.books', compact('books', 'params', 'filters', 'author'));
    }
}
