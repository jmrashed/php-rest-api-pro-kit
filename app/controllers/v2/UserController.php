<?php

namespace App\Controllers\V2;

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
        
        // V2 enhanced response format with metadata
        return Response::json([
            'success' => true,
            'data' => $users,
            'meta' => [
                'version' => 'v2',
                'timestamp' => date('c'),
                'pagination' => [
                    'page' => (int)$page,
                    'per_page' => (int)$perPage
                ]
            ]
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $this->userService->getUserById($id);
        if ($user) {
            return Response::json([
                'success' => true,
                'data' => $user,
                'meta' => [
                    'version' => 'v2',
                    'timestamp' => date('c')
                ]
            ]);
        }
        
        return Response::json([
            'success' => false,
            'error' => [
                'code' => 'USER_NOT_FOUND',
                'message' => 'User not found'
            ],
            'meta' => [
                'version' => 'v2',
                'timestamp' => date('c')
            ]
        ], 404);
    }

    public function store(Request $request)
    {
        $data = $request->getJsonBody();
        $result = $this->userService->createUser($data);

        if (isset($result['errors'])) {
            return Response::json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_FAILED',
                    'message' => 'Validation failed',
                    'details' => $result['errors']
                ],
                'meta' => [
                    'version' => 'v2',
                    'timestamp' => date('c')
                ]
            ], 422);
        }

        if ($result) {
            return Response::json([
                'success' => true,
                'data' => ['id' => $result['id']],
                'meta' => [
                    'version' => 'v2',
                    'timestamp' => date('c'),
                    'action' => 'created'
                ]
            ], 201);
        }
        
        return Response::json([
            'success' => false,
            'error' => [
                'code' => 'CREATION_FAILED',
                'message' => 'Failed to create user'
            ],
            'meta' => [
                'version' => 'v2',
                'timestamp' => date('c')
            ]
        ], 500);
    }

    public function update(Request $request, $id)
    {
        $data = $request->getJsonBody();
        $result = $this->userService->updateUser($id, $data);

        if (isset($result['errors'])) {
            return Response::json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_FAILED',
                    'message' => 'Validation failed',
                    'details' => $result['errors']
                ],
                'meta' => [
                    'version' => 'v2',
                    'timestamp' => date('c')
                ]
            ], 422);
        }

        if ($result) {
            return Response::json([
                'success' => true,
                'meta' => [
                    'version' => 'v2',
                    'timestamp' => date('c'),
                    'action' => 'updated'
                ]
            ]);
        }
        
        return Response::json([
            'success' => false,
            'error' => [
                'code' => 'UPDATE_FAILED',
                'message' => 'Failed to update user'
            ],
            'meta' => [
                'version' => 'v2',
                'timestamp' => date('c')
            ]
        ], 500);
    }

    public function destroy(Request $request, $id)
    {
        if ($this->userService->deleteUser($id)) {
            return Response::json([
                'success' => true,
                'meta' => [
                    'version' => 'v2',
                    'timestamp' => date('c'),
                    'action' => 'deleted'
                ]
            ], 204);
        }
        
        return Response::json([
            'success' => false,
            'error' => [
                'code' => 'DELETION_FAILED',
                'message' => 'Failed to delete user'
            ],
            'meta' => [
                'version' => 'v2',
                'timestamp' => date('c')
            ]
        ], 500);
    }
}