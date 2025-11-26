<?php

namespace Helpers;

class Response
{
    public static function success($data = [], $message = 'Request was successful', $statusCode = 200)
    {
        http_response_code($statusCode);

        echo count($data) > 0 ?
            json_encode(['status' => 'success', 'message' => $message, 'data' => $data]) :
            json_encode(['status' => 'success', 'message' => $message]);
    }

    public static function error($message = '', $statusCode = 400, $data = [])
    {
        http_response_code($statusCode);

        echo count($data) > 0 ?
            json_encode(['status' => 'error', 'message' => $message, 'data' => $data]) :
            json_encode(['status' => 'error', 'message' => $message]);
        exit();
    }
}