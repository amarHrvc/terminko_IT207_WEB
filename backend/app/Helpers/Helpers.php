<?php

namespace App\Helpers;

use Faker\Factory;
use App\Dao\TenantDao;

class Helpers
{
    /**
     * @param mixed $result
     * @return void
     */
    public static function testOutput(mixed $result): void
    {
        fwrite(STDERR, print_r($result, true));
    }


    /**
     * @param \Faker\Generator|null $faker
     * @return int
     */
    public static function getTenantId(\Faker\Generator $faker): int
    {
        $teanantData = [
            'name' => $faker->company(),
            'slug' => $faker->unique()->slug(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->unique()->companyEmail(),
            'address' => $faker->streetAddress(),
            'city' => $faker->city(),
            'country' => $faker->country(),
            'postal_code' => $faker->postcode(),
            'operating_hours_json' => json_encode(['open' => '08:00', 'close' => '18:00']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return new TenantDao()->create($teanantData);
    }

    public static function getTenantData(\Faker\Generator $faker): array
    {
        return [
            'name' => $faker->company(),
            'slug' => $faker->unique()->slug(),
            'phone' => $faker->phoneNumber(),
            'email' => $faker->unique()->companyEmail(),
            'address' => $faker->streetAddress(),
            'city' => $faker->city(),
            'country' => $faker->country(),
            'postal_code' => $faker->postcode(),
            'operating_hours_json' => json_encode(['open' => '08:00', 'close' => '18:00']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }


    public static function createTestTenantData(): int
    {
        return new TenantDao()->create(self::getTenantData(Factory::create()));
    }
}
