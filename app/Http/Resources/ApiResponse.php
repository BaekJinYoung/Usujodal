<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponse
{
    public static function success($data = [], $message = 'Success', $searchField = null, $search = null, $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if (!is_null($searchField)) {
            $response['search'] = $search;
        }

        return response()->json($response, $status);
    }

    public static function error($message = 'Error', $status = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }
}
