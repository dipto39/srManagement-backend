<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($data = null, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function error($message = 'Error', $status = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $status);
    }
}
