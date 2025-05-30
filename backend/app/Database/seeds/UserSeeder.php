<?php

declare(strict_types=1);

use App\Dao\UserDao;
use App\Helpers\Helpers;
use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
        $userData = Helpers::getUserData(Factory::create(), true);
        var_dump($userData);
        $id = new UserDao()->create($userData);
        var_dump($id);

        $this->table('users')->insert($userData)->save();
    }
}
