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
}
