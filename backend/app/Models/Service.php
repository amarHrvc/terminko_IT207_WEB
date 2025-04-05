<?php

namespace App\Models;

class Service
{
    public int $id;
    public string $name;
    public ?string $description;
    public ?string $price;
    public ?string $duration_minutes;
    public ?string $is_active;
    public $createdAt;
    public $updatedAt;


    public function __construct(
        int $id,
        string $name,
        ?string $description,
        ?string $price,
        ?string $duration_minutes,
        ?string $is_active,
        ?string $createdAt,
        ?string $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->duration_minutes = $duration_minutes;
        $this->is_active = $is_active;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }


    public static function fromArray(array $data): self
    {
        return new self(
            (int)($data['id'] ?? 0),
            $data['name'] ?? '',
            $data['description'] ?? null,
            $data['price'] ?? null,
            $data['duration_minutes'] ?? null,
            $data['is_active'] ?? null,
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'duration_minutes' => $this->duration_minutes,
            'is_active' => $this->is_active,
        ];
    }
}
