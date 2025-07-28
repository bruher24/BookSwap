<?php

namespace App\Http\Controllers;

use App\Enums\BookTypeEnum;
use Illuminate\Http\JsonResponse;

class BookTypeController extends Controller
{
    public function getTypes(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'booktypes' => BookTypeEnum::toPrettyArray()
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
