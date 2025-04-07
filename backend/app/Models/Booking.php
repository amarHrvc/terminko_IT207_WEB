<?php

namespace App\Models;

class Booking
{
    public int $id;
    public int $tenant_id;
    public int $user_id;
    public string $status;
    public string $start_time;
    public string $end_time;
    public float $total_price;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public static function fromArray(array $data): self
    {
        $booking = new self();

        $booking->id = (int)($data['id'] ?? 0);
        $booking->tenant_id = (int)($data['tenant_id'] ?? 0);
        $booking->user_id = (int)($data['user_id'] ?? 0);
        $booking->status = $data['status'] ?? 'pending';
        $booking->start_time = $data['start_time'] ?? '';
        $booking->end_time = $data['end_time'] ?? '';
        $booking->total_price = (float)($data['total_price'] ?? 0.0);
        $booking->created_at = $data['created_at'] ?? null;
        $booking->updated_at = $data['updated_at'] ?? null;

        return $booking;
    }

    public function toArray(): array
    {
        return [
            'tenant_id' => $this->tenant_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
