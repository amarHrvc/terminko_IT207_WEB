<?php

namespace App\Database\seeds;

use Phinx\Seed\AbstractSeed;

class TenantSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'name' => "some name",
                'slug' => 'alex-barbershop',
                'phone' => '123-456-7890',
                'email' => 'contact@alexbarbershop.com',
                'address' => '123 Main St',
                'city' => 'Anytown',
                'country' => 'USA',
                'postal_code' => '12345',
                'operating_hours_json' => json_encode([
                    'monday' => ['open' => '09:00', 'close' => '18:00'],
                    'tuesday' => ['open' => '09:00', 'close' => '18:00'],
                    // ...
                ]),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Add more tenants if needed
        ];

        $this->table('tenants')->insert($data)->save();
    }
}
