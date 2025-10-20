<?php

namespace App\Models;

use App\Core\Model;

class Token extends Model
{
    protected $table = 'tokens';

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, token, expires_at) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$data['user_id'], $data['token'], $data['expires_at']]);
        return $this->db->lastInsertId();
    }

    public function findByToken($token)
    {
        $sql = "SELECT * FROM {$this->table} WHERE token = ? AND expires_at > NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function deleteByToken($token)
    {
        $sql = "DELETE FROM {$this->table} WHERE token = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$token]);
    }

    public function deleteExpired()
    {
        $sql = "DELETE FROM {$this->table} WHERE expires_at <= NOW()";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
}