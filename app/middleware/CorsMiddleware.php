<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

class CorsMiddleware extends Middleware
{
    public function handle(Request $request, callable $next)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        if ($request->method() === 'OPTIONS') {
            Response::json([], 204); // No Content for preflight requests
            return;
        }

        return $next($request);
    }
}