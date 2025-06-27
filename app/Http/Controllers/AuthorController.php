<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request): string
    {
        return Author::all()->toJson(JSON_PRETTY_PRINT);
    }
}
