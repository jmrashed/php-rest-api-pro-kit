<?php

namespace App\Exceptions;

use Throwable;
use App\Core\Response;

class Handler
{
    public function handle(Throwable $exception)
    {
        $code = $exception->getCode();
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();

        // Default to a 500 Internal Server Error if no specific code is set
        $statusCode = ($code >= 100 && $code < 600) ? $code : 500;

        // Log the exception (in a real application, use a proper logging library)
        error_log(sprintf(
            "[%s] Uncaught exception: %s in %s on line %d",
            date('Y-m-d H:i:s'),
            $message,
            $file,
            $line
        ));

        // Prepare error response
        $response = [
            'status' => 'error',
            'message' => 'An unexpected error occurred.',
            'details' => [
                'code' => $statusCode,
                'error' => $message,
                'file' => $file,
                'line' => $line,
            ]
        ];

        // Only show detailed error information in development environment
        if (\App\Config\Env::get('APP_ENV') !== 'development') {
            unset($response['details']['file']);
            unset($response['details']['line']);
            $response['details']['error'] = 'Internal Server Error';
        }

        Response::json($response, $statusCode);
    }
}