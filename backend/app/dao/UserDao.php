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

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        return $user ? new User($user) : null;
    }

    public function create($data):int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        return parent::create($data);

    }
}
