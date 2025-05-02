<?php

namespace App\Models\Factories;

class UserFactory
{
    public function create($data)
    {
        $user = new User(
            $data['id'] ?? null,
            $data['tenant_id'] ?? null,
            $data['name'] ?? null,
            $data['email'] ?? null,
            $data['password'] ?? null,
            $data['phone'] ?? null,
            $data['role'] ?? null,
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );

        return $user;
    }

}