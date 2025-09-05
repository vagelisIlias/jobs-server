<?php

namespace App\ApiResponseTrait;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    public function sendResponse($data, $message, int $code = 200): JsonResponse
    {
    return response()->json([
            'data' => $data,
            'message' => $message
    ], $code);
    }

    public function sendError($message, $errors = [], int $code = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
