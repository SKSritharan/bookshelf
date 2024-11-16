<?php

namespace App\Traits;

trait ApiResponse
{
    protected function responseWithSuccess($message = '', $data = [], $code = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function responseWithError($message = '', $data = [], $code = 400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
