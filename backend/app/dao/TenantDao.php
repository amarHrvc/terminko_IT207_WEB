<?php

namespace App\Dao;

use App\Database\Database;
use App\Models\Tenant;
use PDO;

class TenantDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'tenants';
        $this->modelClass = Tenant::class;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO tenants (name, description, user_id, created_at) 
            VALUES (?, ?, ?, NOW())
        ');

        $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['user_id']
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return $this->executeUpdate($id, $data);
    }

    public function findByUserId(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM tenants WHERE user_id = ?');
        $stmt->execute([$userId]);
        $tenants = [];

        while ($row = $stmt->fetch()) {
            $tenants[] = new Tenant($row);
        }

        return $tenants;
    }
}
