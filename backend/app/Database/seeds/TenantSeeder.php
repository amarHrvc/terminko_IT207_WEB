<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;
use App\Helpers\Helpers;
use Faker\Factory;

class TenantSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {

        $tenantData = Helpers::getTenantData(Factory::create(), true);
        // $tenantId = new TenantDao()->create($tenantData);
        var_dump($tenantData);
        $this->table('tenants')->insert($tenantData)->save();

    }
}
