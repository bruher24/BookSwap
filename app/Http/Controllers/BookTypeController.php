<?php

namespace App\Http\Controllers;

use App\Interfaces\BookTypeServiceInterface;

class BookTypeController extends Controller
{
    public function getTypes(BookTypeServiceInterface $bookTypeService): string
    {
        return $bookTypeService->getAll()->toJson(JSON_PRETTY_PRINT);
    }
}
