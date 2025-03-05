<?php

namespace App\Helpers;

class Responses
{
    public static function success($data = [], $code = 200, $message=''){
        return response()->json([
            'success' => true,
            'code' => (int)$code,
            'data' => (object)$data,
            'message' => (string)$message
        ]);
    }

    public static function error($data = [], $code=422, $message=''){
        return response()->json([
            'success' => false,
            'code' => (int)$code,
            'data' => (object)$data,
            'message' => (string)$message
        ]);
    }
}