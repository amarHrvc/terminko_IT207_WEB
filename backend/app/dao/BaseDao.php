<?php

namespace App\Dao;

use App\Database\Database;
use App\Helpers\Helpers;
use PDO;
use Pest\Plugins\Parallel\Handlers\Pest;

abstract class BaseDao implements DaoInterface
{
    protected PDO $db;
    protected string $table;
    protected string $modelClass;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?object
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();

        Helpers::testOutput($result);
        Helpers::testOutput(' \n --- ' . $this->modelClass);
//
//        return $result ? new $this->modelClass($result) : null;
        return $result ?  $this->modelClass::fromArray($result) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        $results = [];

        while ($row = $stmt->fetch()) {
            $results[] = $this->modelClass::fromArray($row);
        }

        return $results;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }


    protected function executeUpdate(int $id, array $data): bool
    {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . ' WHERE id = ?';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
}
