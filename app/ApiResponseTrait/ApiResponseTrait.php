<?php

namespace App\ApiResponseTrait;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    public function sendResponse($data, $message = '', $code = 200): JsonResponse
    {
       return response()->json([
            'success' => true,
            'code' => $code,
            'data' => $data,
            'message' => $message
       ], $code);
    }

    public function sendError($message, $code = 404, $errors = [],): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code' => $code,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
