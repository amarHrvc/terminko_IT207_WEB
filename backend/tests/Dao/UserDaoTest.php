<?php

use App\Dao\UserDao;
use Tests\Db\DatabaseHelper;
use Faker\Factory;
use App\Helpers\Helpers;

$dao = null;
$userData = null;
$faker = null;

beforeAll(function () use (&$dao, &$faker) {
    $dao = new UserDao();
    $faker = Factory::create();
});

/**
 * @param \Faker\Generator|null $faker
 * @return array
 */
function getUserData(\Faker\Generator $faker): array
{
    return [
        'name' => $faker->name(),
        'email' => $faker->unique()->email(),
        'password' => 'secret123'
    ];
}

beforeEach(function () use (&$faker, &$userData) {
    $userData = getUserData($faker);
});

test('can create a new user', function () use (&$dao, &$faker, &$userData) {
    $id = $dao->create($userData);

    expect($id)->toBeGreaterThan(0);

    $user = $dao->findById($id);
    expect($user)->not->toBeNull()
        ->and($user->getName())->toBe($userData['name'])
        ->and($user->getEmail())->toBe($userData["email"])
        ->and(password_verify($userData['password'], $user->getPasswordHash()))->toBeTrue();
});

it('can find all users', function () use (&$dao, &$faker, &$userData) {
    $id1 = $dao->create($userData);
    $name1 = $userData['name'];
    $userData = getUserData($faker);
    $id2 = $dao->create($userData);
    $name2 = $userData['name'];
    $user1 = $dao->findById($id1);
    $user2 = $dao->findById($id2);

    expect($user1)->not->toBeNull()
        ->and($user1)->toHaveProperty('name')->and($user1->getName())->toBe($name1)
        ->and($user2)->not->toBeNull()
        ->and($user2)->toHaveProperty('name')->and($user2->getName())->toBe($name2);

});

it('can update a user', function () use (&$dao, &$faker, &$userData) {
    $id = $dao->create($userData);

    $dao->update($id, [
        'name' => 'Dave Updated',
//        'email' => 'dave.new@example.com',
    ]);

    $user = $dao->findById($id);
    expect($user->getName())->toBe('Dave Updated')
        ->and($user->getEmail())->toBe($userData['email']);
});

it('can delete a user', function () use (&$dao, &$faker, &$userData) {
    $id = $dao->create($userData);

    $deleted = $dao->delete($id);
    expect($deleted)->toBeTrue();

    $user = $dao->findById($id);
    expect($user)->toBeNull();
});

it('can find a user by email', function () use (&$dao, &$faker, &$userData) {
    $dao->create($userData);

    $user = $dao->findByEmail($userData['email']);
    expect($user)->not->toBeNull();
    expect($user->getName())->toBe($userData['name']);
});

it('returns null when finding non-existent email', function () use (&$dao, &$faker, &$userData) {
    $user = $dao->findByEmail('notfound@example.com');
    expect($user)->toBeNull();
});