<?php

namespace App\Http\Controllers;

use App\Enums\BookTypeEnum;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;

class BookTypeController extends Controller
{
    public function index(): JsonResponse
    {
        return ResponseHelper::successResponse('Success', [
            'booktypes' => BookTypeEnum::toPrettyArray(),
        ]);
    }
}
