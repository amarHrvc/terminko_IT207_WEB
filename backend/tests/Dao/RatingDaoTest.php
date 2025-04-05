<?php

use App\Dao\RatingDao;
use Tests\Db\DatabaseHelper;
use Faker\Factory;
use App\Helpers\Helpers;

$dao = null;
$ratingData = null;
$faker = null;

beforeAll(function () use (&$dao, &$faker) {
    $dao = new RatingDao();
    $faker = Factory::create();
});

beforeEach(function () use (&$faker, &$ratingData) {
    $ratingData = Helpers::getRatingData($faker);
});

test('can add a  new rating', function () use (&$dao, &$faker, &$ratingData) {
    Helpers::testOutput($ratingData);
    $id = $dao->create($ratingData);

    expect($id)->toBeGreaterThan(0);

    $booking = $dao->findById($id);
    expect($booking)->not->toBeNull()
        ->and($booking->rater_user_id)->toBe($ratingData['rater_user_id']);

});

it('can find all ratings', function () use (&$dao, &$faker, &$ratingData) {
    $id1 = $dao->create($ratingData);
    $ratingData = Helpers::getRatingData($faker);
    $id2 = $dao->create($ratingData);

    $services = $dao->findAll();

    expect($services)->not->toBeNull()
        ->and(count($services))->toBeGreaterThanOrEqual(2);

});

it('can update a rating', function () use (&$dao, &$faker, &$ratingData) {
    $id = $dao->create($ratingData);

    $dao->update($id, [
        'rating_value' => 21,
    ]);

    $user = $dao->findById($id);
    expect($user->rating_value)->toBe(21.0);
});

it('can delete a rating', function () use (&$dao, &$faker, &$ratingData) {
    $id = $dao->create($ratingData);

    $deleted = $dao->delete($id);
    expect($deleted)->toBeTrue();

    $user = $dao->findById($id);
    expect($user)->toBeNull();
});

it('can find a service by ...', function () use (&$dao, &$faker, &$ratingData) {
    // TODO: Implement findBy.
});
