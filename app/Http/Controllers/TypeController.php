<?php

namespace App\Http\Controllers;

use App\Models\BookType;

class TypeController extends Controller
{
    public function getTypes(): string
    {
        return BookType::all()->toJson(JSON_PRETTY_PRINT);
    }
}
