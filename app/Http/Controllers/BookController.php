<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Interfaces\BookServiceInterface;
use App\Models\Book;
use App\Models\UsersFavoriteBooks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request, BookServiceInterface $bookService): View
    {
        $filters = $bookService->getFilterFromRequest($request);

        $books = $bookService->where($filters);
        $params = $bookService->params();

        return view('books.index', compact('books', 'params', 'filters'));
    }

    public function store(StoreBookRequest $request, BookServiceInterface $bookService): JsonResponse
    {
        $validated = $request->validated();
        $book = $bookService->create($validated);
        if (!$book) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'ERR' => ['Ошибка при сохранении книги'],
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Книга успешно сохранена',
        ]);
    }

    public function show(Book $book): View
    {
        $user = Auth::user();
        $isBookLiked = false;
        if ($user) {
            $isBookLiked = UsersFavoriteBooks::where('user_id', $user->id)->where('book_id', $book->id)->exists();
        }
        $sellerPhone = $book->user->phone ? $book->user->phone->number : null;
        return view('books.show', compact('book', 'isBookLiked', 'sellerPhone'));
    }

    public function update(UpdateBookRequest $request, BookServiceInterface $bookService, Book $book)
    {
        $validated = $request->validated();
        $bookService->update($book, $validated);
    }

    public function delete(BookServiceInterface $bookService, Book $book): JsonResponse
    {
        if ($bookService->delete($book)) {
            return response()->json([
                'success' => true,
            ]);
        }
        return response()->json([
            'success' => false,
        ]);
    }

    public function getBookData(Book $book): JsonResponse
    {
        return response()->json([
            'success' => true,
            'book' => $book->loadMissing('authors'),
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
