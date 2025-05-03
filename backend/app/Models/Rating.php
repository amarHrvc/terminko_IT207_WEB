<?php

namespace App\Models;

class Rating
{
    public int $id;
    public int $rater_user_id;
    public int $rated_user_id;
    public int $booking_id;
    public float $rating_value;
    public ?string $rating_comment = null;
    public ?string $attendance_status = null;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public static function fromArray(array $data): self
    {
        $rating = new self();

        $rating->id = (int)($data['id'] ?? 0);
        $rating->rater_user_id = (int)($data['rater_user_id'] ?? 0);
        $rating->rated_user_id = (int)($data['rated_user_id'] ?? 0);
        $rating->booking_id = (int)($data['booking_id'] ?? 0);
        $rating->rating_value = (float)($data['rating_value'] ?? 0);
        $rating->rating_comment = $data['rating_comment'] ?? null;
        $rating->attendance_status = $data['attendance_status'] ?? null;
        $rating->created_at = $data['created_at'] ?? null;
        $rating->updated_at = $data['updated_at'] ?? null;

        return $rating;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'rater_user_id' => $this->rater_user_id,
            'rated_user_id' => $this->rated_user_id,
            'booking_id' => $this->booking_id,
            'rating_value' => $this->rating_value,
            'rating_comment' => $this->rating_comment,
            'attendance_status' => $this->attendance_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
