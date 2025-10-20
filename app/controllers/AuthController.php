<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Request;
use App\Core\Application;
use App\Services\AuthService;
use App\Helpers\Validator;
use Exception;

class AuthController extends Controller
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login(Request $request): Response
    {
        $data = $request->json();

        $validator = new Validator($data);
        $validator->validate([
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true, 'min' => 6],
        ]);

        if (!$validator->isValid()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            $result = $this->authService->login($data['email'], $data['password']);
            if ($result) {
                return Response::json($result);
            }
            return Response::json(['error' => 'Invalid credentials'], 401);
        } catch (Exception $e) {
            Application::logger()->error("Login error: " . $e->getMessage());
            return Response::json(['error' => 'An unexpected error occurred during login.'], 500);
        }
    }

    public function register(Request $request): Response
    {
        $data = $request->json();

        $validator = new Validator($data);
        $validator->validate([
            'name' => ['required' => true, 'min' => 3],
            'email' => ['required' => true, 'email' => true],
            'password' => ['required' => true, 'min' => 6],
        ]);

        if (!$validator->isValid()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        try {
            $result = $this->authService->register($data);
            if ($result) {
                return Response::json($result, 201);
            }
            return Response::json(['error' => 'Registration failed. Email may already be in use.'], 409);
        } catch (Exception $e) {
            Application::logger()->error("Registration error: " . $e->getMessage());
            return Response::json(['error' => 'An unexpected error occurred during registration.'], 500);
        }
    }

    public function logout(Request $request)
    {
        $authHeader = $request->getHeader('Authorization');
        $token = null;
        
        if ($authHeader && strpos($authHeader, 'Bearer ') === 0) {
            $token = substr($authHeader, 7);
        }
        
        if ($token) {
            $this->authService->logout($token);
        }
        
        return Response::json(['message' => 'Logged out successfully']);
    }
}