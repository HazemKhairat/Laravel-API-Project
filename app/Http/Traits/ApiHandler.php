<?php

namespace App\Http\Traits;

trait ApiHandler
{
    function returnError($message, $code)
    {
        return response()->json([
            'status' => false,
            'code' => $code,
            'message' => $message
        ]);
    }

    function returnSuccessMessage($message = "", $code = 200)
    {
        return response()->json([
            'status' => true,
            'code' => $code,
            'message' => $message,

        ]);
    }

    function returnData($key, $value, $message = "", $code = 200)
    {
        return response()->json([
            'status' => true,
            'code' => $code,
            'message' => $message,
            $key => $value,
        ]);
    }
}