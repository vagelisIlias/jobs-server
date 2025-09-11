<?php

namespace App\ApiResponseTrait;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    private function success(string $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
                'message' => $message,
                'status' => $statusCode
        ], $statusCode);
    }

    private function error(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
