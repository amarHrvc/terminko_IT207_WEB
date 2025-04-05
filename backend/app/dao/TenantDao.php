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
            INSERT INTO tenants (
                name, slug, phone, email, address,
                city, country, postal_code,
                operating_hours_json, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ');

        $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $data['city'],
            $data['country'],
            $data['postal_code'],
            $data['operating_hours_json'] ?  $data['operating_hours_json'] : null
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


    public function findByEmail(string $email): ?Tenant
    {
        $stmt = $this->db->prepare('SELECT * FROM tenants WHERE email = ?');
        $stmt->execute([$email]);
        $tenant = $stmt->fetch();

        return $tenant ? Tenant::fromArray($tenant) : null;
    }
}
