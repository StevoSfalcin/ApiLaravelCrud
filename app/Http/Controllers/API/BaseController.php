<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{

    public function sendResponse($result, $message = "", $code = 200)
    {
        $response = [
            'message' => $message,
            'data'    => $result
        ];

        return response()->json($response, $code);
    }

   
    public function sendError($error, $errorMessages = [], $code = 500)
    {
        $response = [
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
