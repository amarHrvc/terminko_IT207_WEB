<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use App\Helpers\CORS;

const ROOT_PATH = __DIR__ . '/../';
// Report all PHP errors
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/..');
$dotenv->load();

Flight::before('start', function() {
    CORS::handle();
});

// Set the application path
Flight::path(__DIR__ . '/../app');


//prep app variable
$app = Flight::app();






require __DIR__ . '/../app/Helpers/FlightMappings.php';


//Extend flight
require __DIR__ . '/../app/dao/daoRegister.php';
require __DIR__ . '/../app/Controller/controllerRegister.php';


//load routes
require __DIR__ . '/../app/routes/routes.php';


// Start the Flight engine
Flight::start();
