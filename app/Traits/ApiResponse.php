<?php

namespace App\Traits;

trait ApiResponse
{
    public static function successResponse($message = 'success', $code = 200)
    {
        return response()->json(['status' => true, 'message' => $message], $code);
    }

    public static function successResponseWithData($data = [], $message = 'success', $code = 200, $token = null)
    {
        if ($token) {
            return response()->json(['status' => true, 'message' => $message, 'data' => $data, 'token' => $token], $code);
        }
        return response()->json(['status' => true, 'message' => $message, 'data' => $data], $code);
    }

    public static function errorResponse($message = 'Something bad happened', $code = 403)
    {
        return response()->json(['status' => false, 'message' => $message], $code);
    }

    public static function errorResponseWithData($data, $message = 'Something bad happened', $code = 403)
    {
        return response()->json(['status' => false, 'message' => $message, 'data' => $data], $code);
    }
}
