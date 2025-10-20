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
            return Response::error('Validation failed', 422, $result['errors']);
        }

        if ($result) {
            return Response::success('User registered successfully', ['id' => $result['id']], 201);
        }

        return Response::error('Failed to register user', 500);
    }

    public function login(Request $request, Response $response)
    {
        $data = $request->getJsonBody();
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            return Response::error('Email and password are required', 400);
        }

        $user = $this->userService->authenticateUser($email, $password);

        if ($user) {
            JwtHelper::init();
            $token = JwtHelper::generateToken([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'exp' => time() + (3600 * 24) // Token valid for 24 hours
            ]);
            return Response::success('Login successful', ['token' => $token]);
        }

        return Response::error('Invalid credentials', 401);
    }

    public function index(Request $request, Response $response)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $users = $this->userService->getAllUsersPaginated($perPage, $page);
        return Response::success('Users retrieved successfully', $users);
    }

    public function show(Request $request, Response $response, $id)
    {
        $user = $this->userService->getUserById($id);
        if ($user) {
            return Response::success('User retrieved successfully', $user);
        }
        return Response::error('User not found', 404);
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getJsonBody();
        $result = $this->userService->createUser($data);

        if (isset($result['errors'])) {
            return Response::error('Validation failed', 422, $result['errors']);
        }

        if ($result) {
            return Response::success('User created successfully', ['id' => $result['id']], 201);
        }
        return Response::error('Failed to create user', 500);
    }

    public function update(Request $request, Response $response, $id)
    {
        $data = $request->getJsonBody();
        $result = $this->userService->updateUser($id, $data);

        if (isset($result['errors'])) {
            return Response::error('Validation failed', 422, $result['errors']);
        }

        if ($result) {
            return Response::success('User updated successfully');
        }
        return Response::error('Failed to update user', 500);
    }

    public function destroy(Request $request, Response $response, $id)
    {
        if ($this->userService->deleteUser($id)) {
            return Response::success('User deleted successfully', [], 204);
        }
        return Response::error('Failed to delete user', 500);
    }
}