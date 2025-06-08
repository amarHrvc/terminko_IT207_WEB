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

    public function findByEmail(string $email): ?Tenant
    {
        $stmt = $this->db->prepare('SELECT * FROM tenants WHERE email = ?');
        $stmt->execute([$email]);
        $tenant = $stmt->fetch();

        return $tenant ? Tenant::fromArray($tenant) : null;
    }

    public function create(array $data): int
    {
        return parent::create($data);
    }
}
