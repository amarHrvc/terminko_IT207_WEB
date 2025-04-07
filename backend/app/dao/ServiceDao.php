<?php

namespace App\Dao;

use App\Models\Service;

class ServiceDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'services';
        $this->modelClass = Service::class;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO services (
                tenant_id, name, description, price, duration_minutes, is_active, 
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ');

        $stmt->execute([
            $data['tenant_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['duration_minutes'],
            $data['is_active'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return $this->executeUpdate($id, $data);
    }
}