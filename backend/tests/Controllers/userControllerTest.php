<?php

use App\Helpers\Helpers;
use Faker\Factory;

$controller = null;
$data = null;
$faker = null;

beforeAll(function () use (&$controller, &$faker) {

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../..');
    $dotenv->load();
    require __DIR__ . '/../../app/dao/daoRegister.php';

    $controller = new \App\Controller\UserController();
    $faker = Factory::create();
});

beforeEach(function () use (&$faker, &$data) {
//    $authData = Helpers::getAuthData($faker);
    $data = Helpers::getUserData($faker);
});

test('can not register without data', function () use (&$controller, &$faker, &$data) {

    $data = [];

    $register = $controller->register($data);
    expect($register)->toHaveAttribute('error');
});


test('user already exists', function () use (&$controller, &$faker, &$data) {
    $id = $controller->create($data);


    $register = $controller->register($data);
    expect($register)->toHaveAttribute('error')
        ->and($register)->error->toBe("User already exists!");
});

test('can register', function () use (&$controller, &$faker, &$data) {
//    ob_end_flush();

    $register = $controller->register($data);



    expect($register)->toHaveKey('user')
//        ->dump($register)

        ->and($register)->user->not->toBeNull()
        ->and($register)->user->email->toBe($data['email'])
    ;
});

test('can login', function () use (&$controller, &$faker, &$data) {
    $id = $controller->create($data);

    $login = $controller->login($data);
    expect($login)->toHaveKey('user')
        ->and($login)->user->not->toBeNull();
});
