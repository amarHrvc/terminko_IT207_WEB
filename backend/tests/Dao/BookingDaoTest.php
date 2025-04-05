<?php

use App\Helpers\Helpers;
use Faker\Factory;

$dao = null;
$bookingData = null;
$faker = null;

beforeAll(function () use (&$dao, &$faker) {
    $dao = new \App\Dao\BookingDao();
    $faker = Factory::create();
});

beforeEach(function () use (&$faker, &$bookingData) {
    $bookingData = Helpers::getBookingData($faker);
});

test('can add a  new booking', function () use (&$dao, &$faker, &$bookingData) {
    $id = $dao->create($bookingData);

    expect($id)->toBeGreaterThan(0);

    $booking = $dao->findById($id);
    expect($booking)->not->toBeNull()
        ->and($booking->user_id)->toBe($bookingData['user_id']);
});

it('can find all bookings', function () use (&$dao, &$faker, &$bookingData) {
    $id1 = $dao->create($bookingData);
    $bookingData = Helpers::getBookingData($faker);
    $id2 = $dao->create($bookingData);

    $services = $dao->findAll();

    expect($services)->not->toBeNull()
        ->and(count($services))->toBeGreaterThanOrEqual(2);
});

it('can update a booking', function () use (&$dao, &$faker, &$bookingData) {
    $id = $dao->create($bookingData);

    $dao->update($id, [
        'total_price' => 40,
    ]);

    $user = $dao->findById($id);
    expect($user->total_price)->toBe(40.0);
});

it('can delete a booking', function () use (&$dao, &$faker, &$bookingData) {
    $id = $dao->create($bookingData);

    $deleted = $dao->delete($id);
    expect($deleted)->toBeTrue();

    $user = $dao->findById($id);
    expect($user)->toBeNull();
});

it('can find a service by ...', function () use (&$dao, &$faker, &$bookingData) {
    // TODO: Implement findBy.
});
