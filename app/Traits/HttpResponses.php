<?php

namespace App\Traits;

trait HttpResponses
{
    protected function success($data, $trackingId = null, $type = null, $code = 200)
    {
        return response()->json([
            'status' => 'Success',
            'trackingID' => $trackingId,
            'type' => $type,
            'data' => $data,
        ], $code);
    }

    protected function error($message, $code)
    {
        return response()->json([
            'status' => 'Error has occured...',
            'message' => $message,
        ], $code);
    }
}
