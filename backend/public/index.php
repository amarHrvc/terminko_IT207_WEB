<?php

// Report all PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

// Set the application path
Flight::path(__DIR__ . '/../app');


//prep app variable
$app = Flight::app();

//load routes 
require __DIR__ . '/../app/routes/routes.php';


// Start the Flight engine
Flight::start();
