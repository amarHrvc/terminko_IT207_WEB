<?php

use App\Dao\TenantDao;
use App\Helpers\Helpers;
use Faker\Factory;

$dao = null;
$tenantData = null;
$faker = null;

beforeAll(function () use (&$dao, &$faker) {
    $dao = new TenantDao();
    $faker = Factory::create();
});

beforeEach(function () use (&$faker, &$tenantData) {
    $tenantData = Helpers::getTenantData($faker);
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
    $tenantData = Helpers::getTenantData($faker);
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
