<?php

namespace App\Models;

class Tenant
{
    public int $id;
    public string $name;
    public ?string $slug = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $address = null;
    public ?string $city = null;
    public ?string $country = null;
    public ?string $postalCode = null;
    public array $operatingHours = [];
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    /**
     * @param int $id
     * @param string $name
     * @param string|null $slug
     * @param string|null $phone
     * @param string|null $email
     * @param string|null $address
     * @param string|null $city
     * @param string|null $country
     * @param string|null $postalCode
     * @param array $operatingHours
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        int $id,
        string $name,
        ?string $slug,
        ?string $phone,
        ?string $email,
        ?string $address,
        ?string $city,
        ?string $country,
        ?string $postalCode,
        array $operatingHours,
        ?string $createdAt,
        ?string $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $slug;
        $this->phone = $phone;
        $this->email = $email;
        $this->address = $address;
        $this->city = $city;
        $this->country = $country;
        $this->postalCode = $postalCode;
        $this->operatingHours = $operatingHours;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }


    public static function fromArray(array $data): self
    {
        $tenant = new self();
        $tenant->id = (int)($data['id'] ?? 0);
        $tenant->name = $data['name'];
        $tenant->slug = $data['slug'] ?? null;
        $tenant->phone = $data['phone'] ?? null;
        $tenant->email = $data['email'] ?? null;
        $tenant->address = $data['address'] ?? null;
        $tenant->city = $data['city'] ?? null;
        $tenant->country = $data['country'] ?? null;
        $tenant->postalCode = $data['postal_code'] ?? null;
        $tenant->operatingHours = json_decode($data['operating_hours_json'] ?? '[]', true);
        $tenant->createdAt = $data['created_at'] ?? null;
        $tenant->updatedAt = $data['updated_at'] ?? null;
        return $tenant;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->postalCode,
            'operating_hours_json' => json_encode($this->operatingHours)
        ];
    }
}
