<?php

namespace App\Http\Controllers;

use App\Events\MessageReceived;
use App\Models\Author;
use App\Models\Book;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MainController extends Controller
{
    public function test(Request $request)
    {
        $message = new Message([
            'from_id' => 1,
            'to_id' => 1,
            'subject' => 'Test Subject',
            'body' => 'Test Body',
        ]);

        $message->save();

        $message->refresh();

        Log::debug('Запуск события');
        MessageReceived::dispatch($message);
        return view('test');
    }

    public function index(): View
    {
        // TODO: отправка сообщения
//        $msg = (new MessageReceived('This is a test email', '#', Str::uuid()))
//            ->onConnection('redis')
//            ->onQueue('app');
//        Mail::to('glushkovd2424@gmail.com')
//            ->queue($msg);

        // TODO: only for testing
        $books = Book::all();
        return view('home', compact('books'));
    }

    public function about(): View
    {
        return view('about');
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

        return response()->json([
            'success' => true,
            'data' => $found,
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
