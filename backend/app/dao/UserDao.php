<?php

namespace App\Dao;

use App\Models\User;

class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
        $this->modelClass = User::class;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO users (name, email, password_hash, created_at) 
            VALUES (?, ?, ?, NOW())
        ');

        $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT)
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->executeUpdate($id, $data);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        return $user ? new User($user) : null;
    }
}
