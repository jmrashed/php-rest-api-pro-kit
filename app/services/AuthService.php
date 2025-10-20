<?php

namespace App\Services;

use App\Models\User;
use App\Models\Token;
use App\Helpers\JwtHelper;

class AuthService
{
    private $userModel;
    private $tokenModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->tokenModel = new Token();
    }

    public function login($email, $password)
    {
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return false;
        }

        $token = JwtHelper::generate(['user_id' => $user['id']]);
        $this->tokenModel->create([
            'user_id' => $user['id'],
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', time() + 3600)
        ]);

        return [
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ];
    }

    public function register($data)
    {
        if ($this->userModel->findByEmail($data['email'])) {
            return false;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $userId = $this->userModel->create($data);
        
        if ($userId) {
            $user = $this->userModel->find($userId);
            $token = JwtHelper::generate(['user_id' => $userId]);
            
            return [
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email']
                ]
            ];
        }

        return false;
    }

    public function logout($token)
    {
        $this->tokenModel->deleteByToken($token);
    }
}