<?php

use App\Dao\TenantDao;
use Tests\Db\DatabaseHelper;
use Faker\Factory;
use App\Helpers\Helpers;

$dao = null;
$tenantData = null;
$faker = null;

beforeAll(function () use (&$dao, &$faker) {
    $dao = new TenantDao();
    $faker = Factory::create();
});

/**
 * @param \Faker\Generator|null $faker
 * @return array
 */
function getTenantData(\Faker\Generator $faker): array
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

beforeEach(function () use (&$faker, &$tenantData) {
    $tenantData = getTenantData($faker);
});

test('can create a new Tenant', function () use (&$dao, &$faker, &$tenantData) {
    $id = $dao->create($tenantData);

    expect($id)->toBeGreaterThan(0);
    $tenant = $dao->findById($id);
    expect($tenant)->not->toBeNull()
        ->and($tenant->name)->toBe($tenantData['name'])
        ->and($tenant->email)->toBe($tenantData["email"])
        ->and($tenant->phone)->toBe($tenantData["phone"])
        ->and($tenant->address)->toBe($tenantData["address"]);
});

it('can find all tenants', function () use (&$dao, &$faker, &$tenantData) {
    $tenant1 = $dao->create($tenantData);
    $tenantData = getTenantData($faker);
    $tenant2 = $dao->create($tenantData);

    $allTenants = $dao->findAll();

    expect($allTenants)->not->toBeNull()->and(count($allTenants))->toBeGreaterThan(1);
});

it('can update a tenant', function () use (&$dao, &$faker, &$tenantData) {
    $id = $dao->create($tenantData);

    $dao->update($id, [
        'name' => 'Tenant name Updated',
    ]);

    $user = $dao->findById($id);
    expect($user->name)->toBe('Tenant name Updated')
        ->and($user->email)->toBe($tenantData['email']);
});

it('can delete a tenant', function () use (&$dao, &$faker, &$tenantData) {
    $id = $dao->create($tenantData);

    $deleted = $dao->delete($id);
    expect($deleted)->toBeTrue();

    $user = $dao->findById($id);
    expect($user)->toBeNull();
});

it('can find a tenant by email', function () use (&$dao, &$faker, &$tenantData) {
    $dao->create($tenantData);

    $user = $dao->findByEmail($tenantData['email']);
    expect($user)->not->toBeNull();
    expect($user->name)->toBe($tenantData['name']);
});

it('returns null when finding non-existent email', function () use (&$dao, &$faker, &$tenantData) {
    $tenant = $dao->findByEmail('notfound@example.com');
    expect($tenant)->toBeNull();
});
