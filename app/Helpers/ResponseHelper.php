<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper
{
    public static function successResponse(array $data = [], string $message = null): JsonResponse
    {
        $data['success'] = true;
        if (isset($message)) {
            $data['systemMessage'] = $message;
        }

        return response()->json($data, 200, [
            'Content-type' => 'application/json'
        ], JSON_PRETTY_PRINT);
    }

    public static function errorResponse(array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'errors' => $errors
        ], 404, [
            'Content-type' => 'application/json'
        ], JSON_PRETTY_PRINT);
    }
}
