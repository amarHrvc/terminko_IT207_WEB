<?php

namespace App\Dao;

use App\Models\Rating;

class RatingDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'ratings';
        $this->modelClass = Rating::class;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO ratings (
                rater_user_id,
                rated_user_id,
                booking_id,
                rating_value,
                rating_comment,
                attendance_status,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ');

        $stmt->execute([
            $data['rater_user_id'],
            $data['rated_user_id'],
            $data['booking_id'],
            $data['rating_value'],
            $data['rating_comment'],
            $data['attendance_status'],
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        return $this->executeUpdate($id, $data);
    }
}