<?php

namespace App\Http\Controllers;

use App\Events\MessageReceived;
use App\Events\MessageSent;
use App\Models\Author;
use App\Models\Book;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MainController extends Controller
{
    public function test()
    {
        $message = Chat::first()->messages()->create([
            'from_id' => 2,
            'to_id' => 1,
            'subject' => '123',
            'body' => 'New Test Body',
        ]);
        MessageSent::dispatch($message);
    }

    public function index(): View
    {
        // TODO: отправка сообщения по email
//        $msg = (new MessageReceived('This is a test email', '#', Str::uuid()))
//            ->onConnection('redis')
//            ->onQueue('app');
//        Mail::to('glushkovd2424@gmail.com')
//            ->queue($msg);

        // TODO: only for testing
        $books = Book::all();
        return view('main.home', compact('books'));
    }

    public function about(): View
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

        return response()->json([
            'success' => true,
            'data' => $found,
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
