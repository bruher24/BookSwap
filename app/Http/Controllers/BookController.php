<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
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
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при сохранении книги',
            ]);
        }
        return ResponseHelper::successResponse('Книга успешно сохранена');
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

    public function update(UpdateBookRequest $request, BookServiceInterface $bookService, Book $book): JsonResponse
    {
        $validated = $request->validated();
        $updated = $bookService->update($book, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении книги'
            ]);
        }
        return ResponseHelper::successResponse('Книга успешно обновлена');
    }

    public function delete(BookServiceInterface $bookService, Book $book): JsonResponse
    {
        if (!$bookService->delete($book)) {
            return ResponseHelper::errorResponse([
                'err' => 'Ошибка при удалении книги'
            ]);
        }
        return ResponseHelper::successResponse('Книга успешно удалена');
    }

    public function getBookData(Book $book): JsonResponse
    {
        return ResponseHelper::successResponse('Success', [
            'book' => $book->loadMissing('authors'),
        ]);
    }
}
