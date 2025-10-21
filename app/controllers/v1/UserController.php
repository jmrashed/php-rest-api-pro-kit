<?php

namespace App\Controllers\V1;

use App\Core\Request;
use App\Core\Response;
use App\Core\Controller;
use App\Services\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index(Request $request)
    {
        $perPage = $_GET['per_page'] ?? 10;
        $page = $_GET['page'] ?? 1;
        $users = $this->userService->getAllUsersPaginated($perPage, $page);
        
        // V1 response format
        return Response::json([
            'status' => 'success',
            'data' => $users,
            'version' => 'v1'
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $this->userService->getUserById($id);
        if ($user) {
            return Response::json([
                'status' => 'success',
                'data' => $user,
                'version' => 'v1'
            ]);
        }
        return Response::error('User not found', 404);
    }

    public function store(Request $request)
    {
        $data = $request->getJsonBody();
        $result = $this->userService->createUser($data);

        if (isset($result['errors'])) {
            return Response::error('Validation failed', 422, $result['errors']);
        }

        if ($result) {
            return Response::json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => ['id' => $result['id']],
                'version' => 'v1'
            ], 201);
        }
        return Response::error('Failed to create user', 500);
    }

    public function update(Request $request, $id)
    {
        $data = $request->getJsonBody();
        $result = $this->userService->updateUser($id, $data);

        if (isset($result['errors'])) {
            return Response::error('Validation failed', 422, $result['errors']);
        }

        if ($result) {
            return Response::json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'version' => 'v1'
            ]);
        }
        return Response::error('Failed to update user', 500);
    }

    public function destroy(Request $request, $id)
    {
        if ($this->userService->deleteUser($id)) {
            return Response::json([
                'status' => 'success',
                'message' => 'User deleted successfully',
                'version' => 'v1'
            ], 204);
        }
        return Response::error('Failed to delete user', 500);
    }
}