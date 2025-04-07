<?php

namespace App\Routes;

use App\Controller\UserController;
use Flight;
use Flight\Engine;

/** @var Engine $app */

$router = $app->router();

// Test route
Flight::route('/', function () {
    Flight::json([
        'message' => 'Welcome to Terminko API',
        'status' => 'running'
    ]);
});

Flight::route('/test', function () {
    echo 'test !!';
});

FLight::resource('/users', UserController::class);

// $router->get('/', function() {
//     echo 'Hello World';
// });
