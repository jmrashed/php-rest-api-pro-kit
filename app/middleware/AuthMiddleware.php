<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Helpers\JwtHelper;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request, callable $next)
    {
        JwtHelper::init(); // Initialize JWT Helper with secret key from .env

        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            Response::json(['status' => 'error', 'message' => 'Authorization header missing'], 401);
            return;
        }

        list($type, $token) = explode(' ', $authHeader, 2);

        if (strtolower($type) !== 'bearer') {
            Response::json(['status' => 'error', 'message' => 'Invalid authorization type'], 401);
            return;
        }

        $decodedToken = JwtHelper::validateToken($token);

        if (!$decodedToken) {
            Response::json(['status' => 'error', 'message' => 'Invalid or expired token'], 401);
            return;
        }

        // Attach user data to the request for controllers to use
        $request->setUser($decodedToken);

        return $next($request);
    }
}