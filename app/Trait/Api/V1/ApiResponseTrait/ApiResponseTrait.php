<?php

namespace App\Trait\Api\V1\ApiResponseTrait;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    private function success(array $data,  string $message, int $statusCode = 200): JsonResponse
    {
        $response = $data;
        $response['message'] = $message;
        $response['status'] = $statusCode;

        return response()->json($response, $statusCode);
    }

    private function error(string $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    public function data($data): JsonResponse
    {
        return response()->json($data);
    }
}
