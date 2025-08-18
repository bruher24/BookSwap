<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\FiltersResource;
use App\Http\Resources\ParamsResource;
use App\Interfaces\BookServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request, BookServiceInterface $bookService): JsonResponse
    {
        $filters = $bookService->getFilterFromRequest($request);
        $books = $bookService->where($filters);
        $params = $bookService->params();


        return ResponseHelper::successResponse([
            'books' => $books,
            'params' => $params,
            'filters' => $filters,
        ]);


        $filtersResource = FiltersResource::collection($books);
        $bookResourceCollection = BookResource::collection($books);
        $paramsResource = ParamsResource::collection($params);

        return ResponseHelper::successResponse([
            'books' => $bookResourceCollection,
            'params' => $paramsResource,
            'filters' => $filtersResource,
        ]);
    }

    public function store(StoreBookRequest $request, BookServiceInterface $bookService): JsonResponse
    {
        $validated = $request->validated();
        $book = $bookService->create($validated);
        $bookResource = new BookResource($book);
        if (!$book) {
            return ResponseHelper::errorResponse(['Ошибка при создании книги']);
        }
        return ResponseHelper::successResponse([
            'book' => $bookResource,
        ], 'Книга успешно создана');
    }

    public function show(BookServiceInterface $bookService, string $id): JsonResponse
    {
        $book = $bookService->get($id);
        $bookResource = new BookResource($book);
        if (!$book) {
            return ResponseHelper::errorResponse(['Ошибка при получении книги']);
        }

        // TODO: в отдельный запрос
        $user = Auth::user();
        $isBookLiked = false;
        if ($user) {
            $isBookLiked = UsersFavoriteBooks::where('user_id', $user->id)->where('book_id', $book->id)->exists();
        }
        /////////

        // TODO: в отдельный запрос
        $sellerPhone = $book->user->phone ? $book->user->phone->number : null;
        /////////

        return ResponseHelper::successResponse([
            'book' => $bookResource,
            'isBookLiked' => $isBookLiked,
            'sellerPhone' => $sellerPhone,
        ]);
    }

    public function update(UpdateBookRequest $request, BookServiceInterface $bookService, string $id): JsonResponse
    {
        $validated = $request->validated();
        $updated = $bookService->update($book, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении книги']);
        }
        return ResponseHelper::successResponse([], 'Книга успешно обновлена');
    }

    public function destroy(BookServiceInterface $bookService, string $id): JsonResponse
    {
        if (!$bookService->delete($book)) {
            return ResponseHelper::errorResponse(['Ошибка при удалении книги']);
        }
        return ResponseHelper::successResponse([], 'Книга успешно удалена');
    }
}
