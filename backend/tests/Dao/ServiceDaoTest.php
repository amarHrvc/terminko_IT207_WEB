<?php

use App\Dao\ServiceDao;
use App\Dao\UserDao;
use Tests\Db\DatabaseHelper;
use Faker\Factory;
use App\Helpers\Helpers;

$dao = null;
$serviceData = null;
$faker = null;

beforeAll(function () use (&$dao, &$faker) {
    $dao = new ServiceDao();
    $faker = Factory::create();
});



beforeEach(function () use (&$faker, &$serviceData) {
    $serviceData = Helpers::getServiceData($faker);
});

test('can add a  new service', function () use (&$dao, &$faker, &$serviceData) {
    $id = $dao->create($serviceData);

    expect($id)->toBeGreaterThan(0);

    $service = $dao->findById($id);
    expect($service)->not->toBeNull()
        ->and($service->name)->toBe($serviceData['name'])
        ->and($service->price)->toEqual($serviceData["price"]);
});

it('can find all services', function () use (&$dao, &$faker, &$serviceData) {
    $id1 = $dao->create($serviceData);
    $serviceData = Helpers::getServiceData($faker);
    $id2 = $dao->create($serviceData);

    $services = $dao->findAll();

    expect($services)->not->toBeNull()
        ->and(count($services))->toBeGreaterThanOrEqual(2);

});

it('can update a service', function () use (&$dao, &$faker, &$serviceData) {
    $id = $dao->create($serviceData);

    $dao->update($id, [
        'name' => 'Service Updated',
    ]);

    $user = $dao->findById($id);
    expect($user->name)->toBe('Service Updated')
        ->and($user->price)->toEqual($serviceData['price']);
});

it('can delete a service', function () use (&$dao, &$faker, &$serviceData) {
    $id = $dao->create($serviceData);

    $deleted = $dao->delete($id);
    expect($deleted)->toBeTrue();

    $user = $dao->findById($id);
    expect($user)->toBeNull();
});

it('can find a service by ...', function () use (&$dao, &$faker, &$serviceData) {
    // TODO: Implement findBy.
});
