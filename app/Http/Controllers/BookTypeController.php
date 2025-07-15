<?php

namespace App\Http\Controllers;

use App\Services\BookTypeService;

class BookTypeController extends Controller
{
    public function getTypes(BookTypeService $bookTypeService): string
    {
        return $bookTypeService->getAll()->toJson(JSON_PRETTY_PRINT);
    }
}
