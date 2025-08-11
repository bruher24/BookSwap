<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function successResponse($message, $data = []): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];
        $response = array_merge($response, $data);
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    public static function errorResponse($errors): JsonResponse
    {
        return response()->json([
            'success' => false,
            'errors' => $errors
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
