<?php

namespace App\Http\Controllers;

use App\Enums\BookTypeEnum;

class BookTypeController extends Controller
{
    public function getTypes(): string
    {
        return json_encode(BookTypeEnum::cases(), JSON_PRETTY_PRINT);
    }
}
