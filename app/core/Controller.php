<?php

namespace App\Core;

class Controller
{
    protected function jsonResponse($data, $statusCode = 200)
    {
        Response::json($data, $statusCode);
    }

    protected function errorResponse($message, $statusCode = 500)
    {
        Response::json(['status' => 'error', 'message' => $message], $statusCode);
    }

    protected function successResponse($message, $data = [], $statusCode = 200)
    {
        Response::json(['status' => 'success', 'message' => $message, 'data' => $data], $statusCode);
    }
}