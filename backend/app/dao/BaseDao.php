<?php

namespace App\Dao;

use App\Database\Database;
use App\Helpers\Helpers;
use App\Models\User;
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

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();


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

    function update(int $id, array $data): bool
    {
        list($fields, $values) = $this->prepeareArayDataForSql($data, true);

        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . ' WHERE id = ?';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function create(array $data): int
    {
        list($fields, $values) = $this->prepeareArayDataForSql($data);
        // Build placeholders for prepared statement
        $placeholders = array_fill(0, count($fields), '?');

        // Build SQL string
        $stmt = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $fields),
            implode(', ', $placeholders)
        );

        $stmt = $this->db->prepare($stmt);

        $stmt->execute($values);

        return (int)$this->db->lastInsertId();
    }

    /**
     * @param array $data
     * @return array[]
     */
    private function prepeareArayDataForSql(array $data, bool $forUpdate = false): array
    {
        $fields = [];
        $values = [];

        foreach ($data as $column => $value) {
            if ($forUpdate) {
                $fields[] = "$column = ?";
            } else {
                $fields[] = $column;
            }
            $values[] = $value;
        }

        return [$fields, $values];
    }



}
