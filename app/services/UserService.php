<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\Validator;

class UserService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function getAllUsers()
    {
        return $this->userModel->all();
    }

    public function getAllUsersPaginated($perPage, $page)
    {
        return $this->userModel->paginate($perPage, $page);
    }

    public function getUserById($id)
    {
        return $this->userModel->find($id);
    }

    public function createUser($data)
    {
        $validator = new Validator($data);
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min' => 6],
        ];

        if (!$validator->validate($rules)) {
            return ['errors' => $validator->errors()];
        }

        if ($this->userModel->findByEmail($data['email'])) {
            return ['errors' => ['email' => ['Email already exists.']]];
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $userId = $this->userModel->create($data);
        return $userId ? ['id' => $userId] : false;
    }

    public function updateUser($id, $data)
    {
        $validator = new Validator($data);
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email'],
        ];

        if (!$validator->validate($rules)) {
            return ['errors' => $validator->errors()];
        }

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->userModel->update($id, $data);
    }

    public function deleteUser($id)
    {
        return $this->userModel->delete($id);
    }

    public function authenticateUser($email, $password)
    {
        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}