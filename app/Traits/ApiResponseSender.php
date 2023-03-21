<?php

namespace App\Traits;

use Illuminate\Http\{JsonResponse, Response};

trait ApiResponseSender
{
    public function successResponse($data, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['data' => $data, 'code' => $code], $code);
    }

    public function errorResponse($message, $code): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    public function errorMessage($message, $code): JsonResponse
    {
        return response()->json($message, $code)->header('Content-Type', 'application/json');
    }

    public function successMessage($message, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json($message, $code)->header('Content-Type', 'application/json');
    }
}
