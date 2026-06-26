<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    protected function successResponse(int $status = 200): JsonResponse
    {
        return response()
            ->json(['meta' => []], $status)
            ->header('Content-Type', 'application/vnd.api+json');
    }

    protected function errorResponse(string $detail, int $status = 400): JsonResponse
    {
        return response()->json([
            'errors' => [
                [
                    'status' => (string) $status,
                    'title' => Response::$statusTexts[$status] ?? 'Error',
                    'detail' => $detail,
                ],
            ],
        ], $status)->header('Content-Type', 'application/vnd.api+json');
    }
}
