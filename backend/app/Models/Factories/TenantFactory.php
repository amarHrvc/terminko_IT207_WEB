<?php

namespace App\Models\Factories;

use App\Database\Database;
use App\Models\Tenant;
use Faker\Factory;

class TenantFactory
{
    private \PDO $db;
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }


    public function create($data): Tenant
    {
        $tenantData = [
            'name' => $data['name'] ?? $this->faker->name,
            'email' => $data['email'] ?? $this->faker->unique()->email,
            'address' => $data['address'] ?? $this->faker->address,
            'city' => $data['address'] ?? $this->faker->city,
            'country' => $data['address'] ?? $this->faker->country,
//            'WORD' => $data['address'] ?? $this->faker->WORD,

        ];
        return Tenant::fromArray($tenantData);
    }

}