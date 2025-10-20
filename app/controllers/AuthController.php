<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Services\AuthService;

class AuthController extends Controller
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login()
    {
        $data = $this->getRequestData();
        
        if (!isset($data['email']) || !isset($data['password'])) {
            return Response::json(['error' => 'Email and password required'], 400);
        }

        $result = $this->authService->login($data['email'], $data['password']);
        
        if ($result) {
            return Response::json($result);
        }
        
        return Response::json(['error' => 'Invalid credentials'], 401);
    }

    public function register()
    {
        $data = $this->getRequestData();
        
        if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
            return Response::json(['error' => 'Name, email and password required'], 400);
        }

        $result = $this->authService->register($data);
        
        if ($result) {
            return Response::json($result, 201);
        }
        
        return Response::json(['error' => 'Registration failed'], 400);
    }

    public function logout()
    {
        $token = $this->getBearerToken();
        
        if ($token) {
            $this->authService->logout($token);
        }
        
        return Response::json(['message' => 'Logged out successfully']);
    }
}