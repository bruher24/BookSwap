<?php

namespace App\Http\Controllers;

use App\Services\BookTypeService;

final class BookTypeController extends Controller
{
    public function __construct(private readonly BookTypeService $bookTypeService)
    {
    }

    public function getTypes(): string
    {
        return $this->bookTypeService->getAll()->toJson(JSON_PRETTY_PRINT);
    }
}
