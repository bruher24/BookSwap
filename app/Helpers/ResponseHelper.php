<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function successResponse(array $data = [], string $systemMessage = null): JsonResponse
    {
        $data['success'] = true;
        if (isset($systemMessage)) {
            $data['systemMessage'] = $systemMessage;
        }

        return response()->json($data, 200, [
            'Content-type' => 'application/json'
        ], JSON_PRETTY_PRINT);
    }

    public static function errorResponse(int $statusCode = 400, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'errors' => $errors
        ], $statusCode, [
            'Content-type' => 'application/json'
        ], JSON_PRETTY_PRINT);
    }
}
