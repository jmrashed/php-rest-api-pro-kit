<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Controller;
use App\Services\UserService;
use App\Helpers\JwtHelper;

class UserController extends Controller
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function register(Request $request, Response $response)
    {
        $data = $request->getJsonBody();
        $result = $this->userService->createUser($data);

        if (isset($result['errors'])) {
            return $this->errorResponse($result['errors'], 422);
        }

        if ($result) {
            return $this->successResponse('User registered successfully', ['id' => $result['id']], 201);
        }

        return $this->errorResponse('Failed to register user', 500);
    }

    public function login(Request $request, Response $response)
    {
        $data = $request->getJsonBody();
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return $this->errorResponse('Email and password are required', 400);
        }

        $user = $this->userService->authenticateUser($email, $password);

        if ($user) {
            JwtHelper::init();
            $token = JwtHelper::generateToken([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'exp' => time() + (3600 * 24) // Token valid for 24 hours
            ]);
            return $this->successResponse('Login successful', ['token' => $token]);
        }

        return $this->errorResponse('Invalid credentials', 401);
    }

    public function index(Request $request, Response $response)
    {
        $users = $this->userService->getAllUsers();
        return $this->jsonResponse($users);
    }

    public function show(Request $request, Response $response, $id)
    {
        $user = $this->userService->getUserById($id);
        if ($user) {
            return $this->jsonResponse($user);
        }
        return $this->errorResponse('User not found', 404);
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getJsonBody();
        $result = $this->userService->createUser($data);

        if (isset($result['errors'])) {
            return $this->errorResponse($result['errors'], 422);
        }

        if ($result) {
            return $this->successResponse('User created successfully', ['id' => $result['id']], 201);
        }
        return $this->errorResponse('Failed to create user', 500);
    }

    public function update(Request $request, Response $response, $id)
    {
        $data = $request->getJsonBody();
        $result = $this->userService->updateUser($id, $data);

        if (isset($result['errors'])) {
            return $this->errorResponse($result['errors'], 422);
        }

        if ($result) {
            return $this->successResponse('User updated successfully');
        }
        return $this->errorResponse('Failed to update user', 500);
    }

    public function destroy(Request $request, Response $response, $id)
    {
        if ($this->userService->deleteUser($id)) {
            return $this->successResponse('User deleted successfully', [], 204);
        }
        return $this->errorResponse('Failed to delete user', 500);
    }
}