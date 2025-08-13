<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Helpers\ResponseHelper;
use App\Interfaces\BookServiceInterface;
use App\Models\Author;
use App\Models\Book;
use App\Models\Chat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function test(): void
    {
        $message = Chat::first()->messages()->create([
            'from_id' => 2,
            'to_id' => 1,
            'subject' => '123',
            'body' => 'New Test Body',
        ]);
        MessageSent::dispatch($message);
    }

    public function index(BookServiceInterface $bookService): JsonResponse
    {
        // TODO: отправка сообщения по email
//        $msg = (new MessageReceived('This is a test email', '#', Str::uuid()))
//            ->onConnection('redis')
//            ->onQueue('app');
//        Mail::to('glushkovd2424@gmail.com')
//            ->queue($msg);

        // TODO: only for testing
        $books = $bookService->getAll();
        return view('main.home', compact('books'));
    }

    public function about(): JsonResponse
    {
        return view('main.about');
    }

    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');

        $found = [
            'books' => Book::search($query)->get(),
            'authors' => Author::search($query)->get(),
        ];

        foreach ($found as $key => $category) {
            if ($category->isEmpty()) {
                unset($found[$key]);
            }
        }

        return ResponseHelper::successResponse('Success', [
            'found' => $found,
        ]);
    }
}
