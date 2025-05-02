<?php

namespace App\Dao;

use App\Helpers\Helpers;
use App\Models\Booking;

class BookingDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'bookings';
        $this->modelClass = Booking::class;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO bookings (
                tenant_id,
                user_id,
                status,
                start_time,
                end_time,
                total_price,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ');

        $stmt->execute([
            $data['tenant_id'],
            $data['user_id'],
            $data['status'],
            $data['start_time'],
            $data['end_time'],
            $data['total_price'],
        ]);

        return (int)$this->db->lastInsertId();
    }


}
