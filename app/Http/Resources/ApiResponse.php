<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse
{
    public static function success($data = [], $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }

    public static function error($message = 'Error', $status = 400)
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], $status);
    }
}
