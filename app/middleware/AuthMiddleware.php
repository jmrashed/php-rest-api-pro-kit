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
        $tokenModel = new \App\Models\Token();

        $authHeader = $request->getHeader('Authorization');

        if (!$authHeader) {
            return Response::json(['status' => 'error', 'message' => 'Authorization header missing'], 401);
        }

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return Response::json(['status' => 'error', 'message' => 'Invalid authorization format'], 401);
        }

        $token = $matches[1];

        $decodedToken = JwtHelper::validateToken($token);

        if (!$decodedToken) {
            return Response::json(['status' => 'error', 'message' => 'Invalid or expired token'], 401);
        }

        // Check if the token exists in the database (not logged out)
        if (!$tokenModel->findByToken($token)) {
            return Response::json(['status' => 'error', 'message' => 'Token revoked or expired'], 401);
        }

        // Attach user data to the request for controllers to use
        $request->setUser($decodedToken);

        return $next($request);
    }
}